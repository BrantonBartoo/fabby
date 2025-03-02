<?php
session_start();
include 'config.php'; // Database connection

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch potential matches
$stmt = $conn->prepare("SELECT id, full_name, age, gender, preferences, profile_picture FROM users WHERE id != ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$matches = $stmt->get_result();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find a Roommate</title>
    <link rel="stylesheet" href="stylez.css">
    <style>
        /* Page Styling */
        /* General Page Styling */
body {
    font-family: Arial, sans-serif;
    background-color: #f0f8ff;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
}

/* Matches Container */
.matches-container {
    width: 90%;
    max-width: 450px;
    height: 600px;
    overflow: hidden;
    position: relative;
    border-radius: 12px;
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    background: white;
    padding: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Individual Match Card */
.match-card {
    width: 100%;
    height: 100%;
    padding: 20px;
    text-align: center;
    position: absolute;
    transition: transform 0.4s ease, opacity 0.4s ease;
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    display: flex;
    flex-direction: column;
    align-items: center;  /* Ensures everything is centered horizontally */
    justify-content: center; /* Centers content vertically */
}

/* Profile Picture (Fully Centered) */
.match-photo {
    width: 180px;  /* Adjust size */
    height: 180px;
    object-fit: cover;
    border-radius: 50%;
    margin-bottom: 20px; /* Space before details */
    display: block;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

/* Text Styling */
.match-card h2 {
    color: #333;
    font-size: 24px;
    margin-bottom: 10px;
    text-align: center;
}

/* Bold Profile Details */
.match-card p {
    font-size: 18px;
    color: #444;
    font-weight: bold;
    margin: 5px 0;
    text-align: center;
}

/* View Profile Button */
.view-profile-btn {
    display: block;
    padding: 12px 20px;
    text-decoration: none;
    background-color: #007bff;
    color: white;
    border-radius: 8px;
    text-align: center;
    font-weight: bold;
    transition: background 0.3s ease;
    margin-top: 15px;
}

.view-profile-btn:hover {
    background-color: #0056b3;
}

/* Swipe Buttons */
.swipe-buttons {
    display: flex;
    justify-content: space-between;
    position: absolute;
    bottom: 20px;
    width: 90%;
    left: 50%;
    transform: translateX(-50%);
}

/* Individual Button */
.swipe-btn {
    padding: 12px 20px;
    border: none;
    cursor: pointer;
    font-size: 18px;
    border-radius: 50%;
    width: 60px;
    height: 60px;
    transition: transform 0.2s ease, background 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
}

/* Left (Reject) Button */
.swipe-btn.left {
    background-color: #ff4d4d;
    color: white;
}

.swipe-btn.left:hover {
    background-color: #e63939;
}

.swipe-btn.left:active {
    transform: scale(0.9);
}

/* Right (Accept) Button */
.swipe-btn.right {
    background-color: #4caf50;
    color: white;
}

.swipe-btn.right:hover {
    background-color: #3c8c40;
}

.swipe-btn.right:active {
    transform: scale(0.9);
}

/* Cursor Sensitivity */
.match-card:hover {
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
    transform: scale(1.02);
}

.match-photo:hover {
    transform: scale(1.05);
    transition: transform 0.3s ease;
}

/* Back Button Container */
.back-button-container {
    text-align: center;
    margin-bottom: 20px;
}

/* Back Button */
.back-button {
    background-color: #007bff;
    color: white;
    padding: 10px 20px;
    font-size: 16px;
    border-radius: 8px;
    text-decoration: none;
    transition: background 0.3s ease;
    display: inline-block;
}

.back-button:hover {
    background-color: #0056b3;
}

/* Match Card Styling */
.match-card {
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    text-align: center;
    margin-bottom: 20px;
}

/* Profile Picture Styling */
.match-photo {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    object-fit: cover;
    margin-bottom: 15px;
}




    </style>
</head>
<body>
    <div class="matches-container">
        <?php 
        $matches_array = [];
        while ($row = $matches->fetch_assoc()) {
            $matches_array[] = $row;
        }

        foreach ($matches_array as $index => $row): ?>
            <div class="match-card" id="match-<?php echo $index; ?>" style="z-index: <?php echo count($matches_array) - $index; ?>;">
                <img src="uploads/<?php echo htmlspecialchars($row['profile_picture'] ?: 'default.jpg'); ?>" 
                     alt="Profile Picture" class="match-photo">
                <h2><?php echo htmlspecialchars($row['full_name']); ?></h2>
                <p>Age: <?php echo htmlspecialchars($row['age']); ?></p>
                <p>Gender: <?php echo htmlspecialchars($row['gender']); ?></p>
                <p>Shared Interests: <?php echo htmlspecialchars($row['preferences']); ?></p>
                <a href="view-profile.php?id=<?php echo $row['id']; ?>" class="view-profile-btn">View Profile</a>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="swipe-buttons">
        <button class="swipe-btn left" onclick="swipeLeft()">❌ Pass</button>
        <button class="swipe-btn right" onclick="swipeRight()">✅ Accept</button>
    </div>

    <script>
        let currentMatch = 0;
        const totalMatches = <?php echo count($matches_array); ?>;
        
        function swipe(direction) {
            if (currentMatch < totalMatches) {
                let matchCard = document.getElementById("match-" + currentMatch);
                matchCard.style.transform = `translateX(${direction === 'left' ? '-100%' : '100%'})`;
                matchCard.style.opacity = '0';
                currentMatch++;
            }
        }

        function swipeLeft() { swipe('left'); }
        function swipeRight() { swipe('right'); }
    </script>
       
      

</body>
</html>
