<?php
// Include the database connection
include 'db.php'; // Ensure this path points to your `db.php`

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process the form submission
    $username = trim($_POST['username'] ?? '');
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $age = trim($_POST['age'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Validate inputs
    if (empty($username) || empty($first_name) || empty($last_name) || empty($age) || empty($password)) {
        $error_message = "All fields are required!";
    }  else {
        // Insert user into the database
        $stmt = $conn->prepare("INSERT INTO users (username, name, l_name, age, password) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssis", $username, $first_name, $last_name, $age, $password);

        if ($stmt->execute()) {
            $success_message = "Registration successful! You can now log in.";
        } else {
            $error_message = "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <link rel="stylesheet" href="styles/register.css"> <!-- Link to external CSS -->
</head>
<body>
    <div class="form-container">
        <h1>Register</h1>
        
        <?php if (isset($error_message)) { echo "<div class='error-message'>$error_message</div>"; } ?>
        <?php if (isset($success_message)) { echo "<div class='success-message'>$success_message</div>"; } ?>

        <form action="register.php" method="POST">
            <div class="form-group">
                <label for="username">Username (Email):</label>
                <input type="text" id="username" name="username" placeholder="Enter your email" required>
            </div>
            <div class="form-group">
                <label for="first_name">First Name:</label>
                <input type="text" id="first_name" name="first_name" placeholder="Enter your first name" required>
            </div>
            <div class="form-group">
                <label for="last_name">Last Name:</label>
                <input type="text" id="last_name" name="last_name" placeholder="Enter your last name" required>
            </div>
            <div class="form-group">
                <label for="age">Age:</label>
                <input type="number" id="age" name="age" placeholder="Enter your age" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="btn">Register</button>
        </form>

        <p>Already have an account? <a href="user/login.php">Login to Dashboard</a></p>
    </div>
</body>
</html>