<?php
session_start();
include 'config.php'; // Database connection

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details
$stmt = $conn->prepare("SELECT full_name, email, age, gender, profile_picture FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Fetch preferences
$pref_stmt = $conn->prepare("SELECT * FROM preferences WHERE user_id = ?");
$pref_stmt->bind_param("i", $user_id);
$pref_stmt->execute();
$preferences = $pref_stmt->get_result()->fetch_assoc();
$pref_stmt->close();

// Fetch Total Users Looking for Roommates
$total_users_query = "SELECT COUNT(*) AS total FROM users";
$total_users_result = $conn->query($total_users_query);
$total_users_row = $total_users_result->fetch_assoc();
$total_users = $total_users_row['total'];

// Fetch Available Matches for the User
$match_query = $conn->prepare("SELECT COUNT(*) AS matches FROM users WHERE id != ? AND (gender = ? OR gender = 'Other')");
$match_query->bind_param("is", $user_id, $user['gender']);
$match_query->execute();
$match_result = $match_query->get_result();
$match_row = $match_result->fetch_assoc();
$available_matches = $match_row['matches'];
$match_query->close();

// Fetch Recent Activities (Last 5 Users Who Signed Up)
$activities_query = "SELECT full_name, created_at FROM users ORDER BY created_at DESC LIMIT 5";
$activities_result = $conn->query($activities_query);

// Handle Image Upload
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["user_image"])) {
    $targetDir = "uploads/";
    $fileName = basename($_FILES["user_image"]["name"]);
    $targetFilePath = $targetDir . $fileName;
    
    if (move_uploaded_file($_FILES["user_image"]["tmp_name"], $targetFilePath)) {
        $stmt = $conn->prepare("INSERT INTO user_images (user_id, image_path) VALUES (?, ?)");
        $stmt->bind_param("is", $user_id, $fileName);
        $stmt->execute();
        $stmt->close();
    }
}

// Fetch User Images
$images_query = $conn->prepare("SELECT image_path FROM user_images WHERE user_id = ?");
$images_query->bind_param("i", $user_id);
$images_query->execute();
$images_result = $images_query->get_result();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="stylez.css">
    <style>
        body {
    font-family: Arial, sans-serif;
    background-color: #f0f8ff;
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
}

/* Dashboard Container */
.dashboard-container {
    background: white;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    margin: 40px auto;
    width: 85%;
    max-width: 1000px;
    text-align: center;
}

/* Dashboard Title */
.dashboard-container h2 {
    color: #333;
    font-size: 24px;
    margin-bottom: 30px;
}

/* Dashboard Cards */
.dashboard-cards {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 40px; /* Increased spacing between cards */
    margin-bottom: 50px; /* Space before Recent Activities */
}

/* Individual Card */
.dashboard-card {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 10px;
    flex: 1;
    min-width: 280px;
    text-align: center;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

/* Hover Effect */
.dashboard-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
}

/* Card Title */
.dashboard-card h3 {
    color: #007bff;
    font-size: 20px;
    margin-bottom: 10px;
}

/* Card Data */
.dashboard-card p {
    font-size: 16px;
    color: #555;
}

/* Recent Activities Section */
.recent-activities {
    background: white;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
    width: 85%;
    max-width: 1000px;
    margin-top: 50px; /* More spacing from dashboard */
}

/* Recent Activities Title */
.recent-activities h2 {
    color: #333;
    font-size: 22px;
    margin-bottom: 20px;
}

/* Activity Item */
.activity-item {
    background: #f4f4f4;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 10px;
    text-align: left;
}

/* Responsive Design */
@media (max-width: 768px) {
    .dashboard-cards {
        flex-direction: column;
        gap: 20px;
    }
}

/* Preferences Container Hover Effect */
.preferences-container {
    background: white;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease, background-color 0.3s ease;
    cursor: pointer; /* Changes cursor to indicate interactivity */
}

.preferences-container:hover {
    background:rgb(201, 198, 198); /* Light blue background on hover */
    transform: translateY(-5px); /* Slight upward lift */
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2); /* Enhanced shadow effect */
}

/* Preference Items Hover Effect */
.preference-item {
    padding: 15px;
    border-radius: 8px;
    transition: background-color 0.3s ease, transform 0.2s ease;
}

.preference-item:hover {
    background-color: #d6ebff; /* Slightly darker blue on hover */
    transform: scale(1.05); /* Slightly enlarges the item */
}

/* Favorite Images Main Container */
.favorites-container {
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    margin: 30px auto;
    width: 85%;
    max-width: 800px;
    text-align: center;
}

/* Title Styling */
.favorites-container h3 {
    color: #333;
    font-size: 22px;
    margin-bottom: 15px;
}

/* Image Gallery */
.image-gallery {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    justify-content: center;
}

/* Individual Images */
.gallery-image {
    width: 150px;
    height: 150px;
    object-fit: cover;
    border-radius: 10px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

/* Hover Effect */
.gallery-image:hover {
    transform: scale(1.05);
    box-shadow: 0 3px 8px rgba(0, 0, 0, 0.2);
}

/* Responsive Design */
@media (max-width: 600px) {
    .gallery-image {
        width: 120px;
        height: 120px;
    }
}

/* Favorite Images Container */
.favorites-container {
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    margin-top: 30px;
    width: 85%;
    max-width: 800px;
    text-align: center;
}

/* Upload Form Styling */
.upload-form {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-bottom: 20px;
}

.upload-btn {
    margin-top: 10px;
    padding: 8px 15px;
    border: none;
    background: #007bff;
    color: white;
    border-radius: 8px;
    cursor: pointer;
    transition: background 0.3s ease;
}

.upload-btn:hover {
    background: #0056b3;
}

/* Image Gallery */
.image-gallery {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    justify-content: center;
}

/* Individual Image */
.gallery-item {
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
    transition: transform 0.3s ease;
}

.gallery-item:hover {
    transform: scale(1.05);
}

/* Image Styling */
.gallery-image {
    width: 100px;
    height: 100px;
    object-fit: cover;
}


    </style>
</head>
<body>
    <div class="profile-container">
        <!-- Profile Picture -->
        <div class="profile-picture-container">
            <img src="<?php echo !empty($user['profile_picture']) ? 'uploads/' . $user['profile_picture'] : 'uploads/default.png'; ?>" alt="Profile Picture" class="profile-picture">
        </div>

        <!-- User Information -->
        <h2><?php echo htmlspecialchars($user['full_name']); ?></h2>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
        <p><strong>Age:</strong> <?php echo htmlspecialchars($user['age']); ?></p>
        <p><strong>Gender:</strong> <?php echo htmlspecialchars($user['gender']); ?></p>

        <div class="upload-container">
        <h3>📸 Add Your Favorite Images</h3>
<form action="" method="post" enctype="multipart/form-data">
    <input type="file" name="user_image" required>
    <button type="submit">Upload</button>
</form>

</div>





        <!-- Edit Profile Button -->
        <a href="edit-profile.php" class="btn">Edit Profile</a>
        <a href="matches.php" class="find-roommate-btn">Find a Roommate</a>
        <a href="logout.php" class="logout-btn">Log Out</a>
    </div>
    <div class="favorites-container">
    <h3>🎨 Your Favorite Images</h3>
    <div class="image-gallery">
        <?php while ($image = $images_result->fetch_assoc()): ?>
            <img src="uploads/<?php echo htmlspecialchars($image['image_path']); ?>" class="gallery-image">
        <?php endwhile; ?>
    </div>
</div>


    <!-- Preferences Section -->
    <div class="preferences-container">
        <h2>Your Roommate Preferences</h2>
        <?php if ($preferences): ?>
            <div class="preference-card">
                <p><strong>Cooking Habits:</strong> <?php echo htmlspecialchars($preferences['cooking_habits']); ?></p>
                <p><strong>Professional Status:</strong> <?php echo htmlspecialchars($preferences['professional_status']); ?></p>
                <p><strong>Can Stay with Pets:</strong> <?php echo htmlspecialchars($preferences['can stay with pets']); ?></p>
                <p><strong>Preferred Pets:</strong> <?php echo htmlspecialchars($preferences['prefered pets']); ?></p>
                <p><strong>Budget:</strong> <?php echo htmlspecialchars($preferences['budget']); ?></p>
                <p><strong>Personality:</strong> <?php echo htmlspecialchars($preferences['personality']); ?></p>
                <p><strong>Willing to Share Costs:</strong> <?php echo htmlspecialchars($preferences['share_cost']); ?></p>
                <p><strong>Shares Chores:</strong> <?php echo htmlspecialchars($preferences['share_chores']); ?></p>
                <p><strong>Deal Breakers:</strong> <?php echo htmlspecialchars($preferences['deal_breakers']); ?></p>
            </div>
        <?php else: ?>
            <p>No preferences set. <a href="edit-profile.php">Update Preferences</a></p>
        <?php endif; ?>
    </div>

    <div class="dashboard-container">
        <h2>📊 Dashboard Overview</h2>

        <div class="dashboard-cards">
            <div class="dashboard-card">
                <h3>Total Users Looking for Roommates</h3>
                <p><?php echo $total_users; ?> Users</p>
            </div>

            <div class="dashboard-card">
                <h3>Available Matches for You</h3>
                <p><?php echo $available_matches; ?> Matches</p>
            </div>
        </div>
    </div>

    <div class="recent-activities">
        <h3>🕒 Recent Activities</h3>
        <ul>
            <?php while ($activity = $activities_result->fetch_assoc()): ?>
                <li><?php echo htmlspecialchars($activity['full_name']) . " joined on " . date("F j, Y", strtotime($activity['created_at'])); ?></li>
            <?php endwhile; ?>
        </ul>
    </div>
    
</body>
</html>
