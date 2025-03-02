<?php
session_start();
include 'config.php'; // Database connection

if (!isset($_GET['id'])) {
    echo "User ID is missing.";
    exit();
}

$user_id = $_GET['id'];

// Fetch user details
$stmt = $conn->prepare("SELECT full_name, email, age, gender, preferences, profile_picture FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Fetch user's favorite images
$image_stmt = $conn->prepare("SELECT image_path FROM user_images WHERE user_id = ?");
$image_stmt->bind_param("i", $user_id);
$image_stmt->execute();
$images_result = $image_stmt->get_result();
$image_stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Profile</title>
    <style>
    /* Profile Page Styling */
.profile-container {
    background: white;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    text-align: center;
    width: 400px;
    margin: 50px auto;
}

.profile-container h2 {
    color: #333;
    font-size: 22px;
    margin-bottom: 15px;
}

.profile-container p {
    font-size: 16px;
    color: #555;
    margin-bottom: 10px;
}

.profile-picture-container {
    margin-bottom: 20px;
}

.profile-picture {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    object-fit: cover;
}

.btn {
    display: inline-block;
    padding: 10px 20px;
    background: #007BFF;
    color: white;
    border-radius: 6px;
    text-decoration: none;
    margin-top: 10px;
}

.btn:hover {
    background: #0056b3;
}

/* Favorite Images Container */
.favorite-images-container {
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    margin: 30px auto;
    width: 85%;
    max-width: 800px;
    text-align: center;
}

/* Title */
.favorite-images-container h3 {
    color: #333;
    font-size: 22px;
    margin-bottom: 15px;
}

/* Image Gallery */
.image-gallery {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 15px;
}

/* Individual Image */
.gallery-image {
    width: 150px;
    height: 150px;
    object-fit: cover;
    border-radius: 10px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.gallery-image:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
}

/* Back Button Container */
.back-button-container {
    text-align: center;
    margin-top: 20px;
}

/* Back Button */
.back-button {
    background-color: #007bff;
    color: white;
    border: none;
    padding: 10px 20px;
    font-size: 16px;
    border-radius: 8px;
    cursor: pointer;
    transition: background 0.3s ease;
}

.back-button:hover {
    background-color: #0056b3;
}


</style>
</head>
<body>

    <div class="profile-container">
        <!-- Profile Picture -->
        <div class="profile-picture-container">
            <img src="uploads/<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="Profile Picture" class="profile-picture">
        </div>

        <!-- User Information -->
        <h2><?php echo htmlspecialchars($user['full_name']); ?></h2>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
        <p><strong>Age:</strong> <?php echo htmlspecialchars($user['age']); ?></p>
        <p><strong>Gender:</strong> <?php echo htmlspecialchars($user['gender']); ?></p>
        <p><strong>Preferences:</strong> <?php echo htmlspecialchars($user['preferences']); ?></p>
    </div>

    <!-- Favorite Images Section -->
    <div class="favorite-images-container">
        <h3>🎨 Favorite Images</h3>
        <div class="image-gallery">
            <?php while ($image = $images_result->fetch_assoc()): ?>
                <img src="uploads/<?php echo $image['image_path']; ?>" class="gallery-image">
            <?php endwhile; ?>
        </div>
    </div>
    <!-- Back Button -->
    <div class="back-button-container">
        <button onclick="goBack()" class="back-button">⬅ Back</button>
    </div>

    <script>
        function goBack() {
            window.history.back();
        }
    </script>

</body>
</html>













