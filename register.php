<?php
// Database connection
$host = "localhost";
$username = "root"; // Default XAMPP MySQL user
$password = ""; // Default XAMPP password (empty)
$database = "roommate_matching"; // Change to your database name

$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
$message = ""; // Store success or error messages
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // User details
    $full_name = $_POST["full_name"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $age = $_POST["age"];
    $gender = $_POST["gender"];

    // Preferences
    $cooking_habits = $_POST["cooking_habits"];
    $professional_status = $_POST["professional_status"];
    $stay_with_pets = $_POST["stay_with_pets"];
    $pet_preference = $_POST["pet_preference"];
    $budget = $_POST["budget"];
    $personality = $_POST["personality"];
    $share_cost = $_POST["share_cost"];
    $share_chores = $_POST["share_chores"];
    $deal_breakers = $_POST["deal_breakers"];

    // Check if email already exists
    $check_email = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $check_email->bind_param("s", $email);
    $check_email->execute();
    $check_email->store_result();

    if ($check_email->num_rows > 0) {
        $message = "<p class='error'>Error: Email already registered. Please use a different email.</p>";
    } else {
        // Insert user into users table
        $stmt = $conn->prepare("INSERT INTO users (full_name, email, password, age, gender) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssii", $full_name, $email, $password, $age, $gender);

        if ($stmt->execute()) {
            $user_id = $stmt->insert_id; // Get the new user ID

            // Insert user preferences into preferences table
            $stmt_prefs = $conn->prepare("INSERT INTO preferences (user_id, cooking_habits, professional_status, stay_with_pets, pet_preference, budget, personality, share_cost, share_chores, deal_breakers) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt_prefs->bind_param("issssdsiss", $user_id, $cooking_habits, $professional_status, $stay_with_pets, $pet_preference, $budget, $personality, $share_cost, $share_chores, $deal_breakers);

            if ($stmt_prefs->execute()) {
                $message = "<p class='success'>Registration successful! <a href='login.php'>Login here</a></p>";
            } else {
                $message = "<p class='error'>Error saving preferences: " . $stmt_prefs->error . "</p>";
            }

            $stmt_prefs->close();
        } else {
            $message = "<p class='error'>Error: " . $stmt->error . "</p>";
        }

        $stmt->close();
    }

    $check_email->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <style>
        /* Reset default browser styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: Arial, sans-serif;
}

/* Full-page styling */
body {
    background-color: #f4f4f4;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh; /* Ensures full-page height */
    padding: 20px; /* Adds space for small screens */
    overflow-y: auto; /* Allows scrolling if needed */
}

/* Registration container */
.register-container {
    width: 100%;
    max-width: 500px; /* Keeps it readable */
    background-color: white;
    padding: 20px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    text-align: center;
}

/* Form elements */
h1 {
    color: #333;
    margin-bottom: 15px;
}

label {
    display: block;
    margin-top: 10px;
    text-align: left;
    font-weight: bold;
}

input, select, textarea {
    width: 100%;
    padding: 10px;
    margin-top: 5px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
}

/* Button styling */
button {
    width: 100%;
    padding: 12px;
    margin-top: 20px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: 0.3s;
    font-size: 16px;
}

button:hover {
    background-color: #45a049;
}

/* Messages */
.error {
    color: red;
    font-size: 14px;
}

.success {
    color: green;
    font-size: 14px;
}

/* Links */
p {
    margin-top: 15px;
}

p a {
    color: #4CAF50;
    text-decoration: none;
    font-weight: bold;
}

p a:hover {
    text-decoration: underline;
}

/* Responsive Design */
@media (max-width: 600px) {
    .register-container {
        padding: 15px;
    }
}

    </style>
</head>
<body>
    <div class="register-container">
        <h1>Sign Up</h1>
        <?php echo $message; ?>
        <form action="" method="POST">
            <label for="full_name">Full Name:</label>
            <input type="text" id="full_name" name="full_name" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <label for="age">Age:</label>
            <input type="number" id="age" name="age" required>

            <label for="gender">Gender:</label>
            <select id="gender" name="gender">
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select>

            <label for="cooking_habits">Cooking Habits:</label>
            <input type="text" id="cooking_habits" name="cooking_habits" required>

            <label for="professional_status">Professional Status:</label>
            <select id="professional_status" name="professional_status">
                <option value="Employed">Employed</option>
                <option value="Student">Student</option>
                <option value="Freelancer">Freelancer</option>
            </select>

            <label for="stay_with_pets">Can you stay with pets?</label>
            <select id="stay_with_pets" name="stay_with_pets">
                <option value="Yes">Yes</option>
                <option value="No">No</option>
            </select>

            <label for="pet_preference">If yes, which pets?</label>
            <input type="text" id="pet_preference" name="pet_preference">

            <label for="budget">Budget ($):</label>
            <input type="number" id="budget" name="budget" step="0.01" required>

            <label for="personality">Personality:</label>
            <select id="personality" name="personality">
                <option value="Extrovert">Extrovert</option>
                <option value="Introvert">Introvert</option>
            </select>

            <label for="share_cost">Would you share costs?</label>
            <select id="share_cost" name="share_cost">
                <option value="Yes">Yes</option>
                <option value="No">No</option>
            </select>

            <label for="share_chores">Can you share chores?</label>
            <select id="share_chores" name="share_chores">
                <option value="Yes">Yes</option>
                <option value="No">No</option>
            </select>

            <label for="deal_breakers">Deal Breakers in a Roommate:</label>
            <textarea id="deal_breakers" name="deal_breakers"></textarea>

            <button type="submit">Register</button>
        </form>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</body>
</html>
