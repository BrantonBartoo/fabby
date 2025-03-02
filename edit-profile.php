<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch current user details
$stmt = $conn->prepare("SELECT full_name, email, age, gender, preferences, profile_picture FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Handle profile update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update_profile'])) {
        $full_name = $_POST['full_name'];
        $age = $_POST['age'];
        $gender = $_POST['gender'];
        $preferences = $_POST['preferences'];

        $stmt = $conn->prepare("UPDATE users SET full_name=?, age=?, gender=?, preferences=? WHERE id=?");
        $stmt->bind_param("sissi", $full_name, $age, $gender, $preferences, $user_id);

        if ($stmt->execute()) {
            $_SESSION['full_name'] = $full_name; // Update session data
            $success_message = "Profile updated successfully!";
        } else {
            $error_message = "Error updating profile.";
        }
        $stmt->close();
    }

    if (isset($_POST['change_password'])) {
        $old_password = $_POST['old_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($hashed_password);
        $stmt->fetch();
        $stmt->close();

        if (password_verify($old_password, $hashed_password)) {
            if ($new_password === $confirm_password) {
                $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                $stmt = $conn->prepare("UPDATE users SET password=? WHERE id=?");
                $stmt->bind_param("si", $new_hashed_password, $user_id);

                if ($stmt->execute()) {
                    $success_message = "Password changed successfully!";
                } else {
                    $error_message = "Error changing password.";
                }
                $stmt->close();
            } else {
                $error_message = "New passwords do not match.";
            }
        } else {
            $error_message = "Incorrect old password.";
        }
    }

    if (isset($_POST['upload_picture'])) {
        if ($_FILES['profile_picture']['error'] == 0) {
            $target_dir = "uploads/";
            $file_name = basename($_FILES["profile_picture"]["name"]);
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            $allowed_types = ["jpg", "jpeg", "png"];

            if (in_array($file_ext, $allowed_types)) {
                $new_file_name = "profile_" . $user_id . "." . $file_ext;
                $target_file = $target_dir . $new_file_name;

                if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
                    $stmt = $conn->prepare("UPDATE users SET profile_picture=? WHERE id=?");
                    $stmt->bind_param("si", $new_file_name, $user_id);
                    if ($stmt->execute()) {
                        $success_message = "Profile picture updated successfully!";
                        $user['profile_picture'] = $new_file_name;
                    } else {
                        $error_message = "Error updating profile picture.";
                    }
                    $stmt->close();
                } else {
                    $error_message = "Error uploading file.";
                }
            } else {
                $error_message = "Invalid file type. Only JPG, JPEG, and PNG allowed.";
            }
        } else {
            $error_message = "No file uploaded or error occurred.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="profile-edit-container">
        <h1>Edit Profile</h1>
        
        <?php if (isset($success_message)) echo "<p class='success'>$success_message</p>"; ?>
        <?php if (isset($error_message)) echo "<p class='error'>$error_message</p>"; ?>

        <!-- Profile Picture Upload -->
        <h2>Profile Picture</h2>
        <img src="uploads/<?php echo $user['profile_picture'] ?? 'default.png'; ?>" alt="Profile Picture" class="profile-pic">
        
        <form action="edit-profile.php" method="POST" enctype="multipart/form-data">
            <input type="file" name="profile_picture" required>
            <button type="submit" name="upload_picture" class="btn">Upload Picture</button>
        </form>

        <!-- Profile Update Form -->
        <form action="edit-profile.php" method="POST">
            <label for="full_name">Full Name:</label>
            <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>

            <label for="age">Age:</label>
            <input type="number" id="age" name="age" value="<?php echo htmlspecialchars($user['age'] ?? ''); ?>">

            <label for="gender">Gender:</label>
            <select id="gender" name="gender">
                <option value="Male" <?php echo ($user['gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                <option value="Female" <?php echo ($user['gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                <option value="Other" <?php echo ($user['gender'] == 'Other') ? 'selected' : ''; ?>>Other</option>
            </select>

            <label for="preferences">Roommate Preferences:</label>
            <textarea id="preferences" name="preferences"><?php echo htmlspecialchars($user['preferences'] ?? ''); ?></textarea>

            <button type="submit" name="update_profile" class="btn">Update Profile</button>
        </form>

        <!-- Change Password Form -->
        <h2>Change Password</h2>
        <form action="edit-profile.php" method="POST">
            <label for="old_password">Current Password:</label>
            <input type="password" id="old_password" name="old_password" required>

            <label for="new_password">New Password:</label>
            <input type="password" id="new_password" name="new_password" required>

            <label for="confirm_password">Confirm New Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>

            <button type="submit" name="change_password" class="btn">Change Password</button>
        </form>

        <a href="profile.php">Back to Profile</a>
    </div>
</body>
</html>
