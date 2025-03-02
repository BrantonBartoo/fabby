<?php
session_start();
include 'config.php'; // Database connection

if (!isset($_SESSION['user_id']) || !isset($_POST['selected_user_id'])) {
    echo "Error: Unauthorized request.";
    exit();
}

$user_id = $_SESSION['user_id'];
$selected_user_id = $_POST['selected_user_id'];

// Insert into matches table
$stmt = $conn->prepare("INSERT INTO matches (user_id, matched_user_id) VALUES (?, ?)");
$stmt->bind_param("ii", $user_id, $selected_user_id);
if ($stmt->execute()) {
    echo "Match saved!";
} else {
    echo "Error: " . $conn->error;
}
$stmt->close();
$conn->close();
?>
