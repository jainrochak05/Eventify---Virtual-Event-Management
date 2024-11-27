<?php
session_start();
include '../db.php'; // Include the database connection file

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query to check user credentials
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // If credentials are valid, fetch the user ID
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['id']; // Set session variable

        // Redirect to the user dashboard
        header('Location: user_dash.php');
        exit;
    } else {
        // Invalid credentials error message
        $error = 'Invalid username or password!';
    }

    // Close statement
    $stmt->close();
}

// Close database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <link rel="stylesheet" href="../styles/user_login.css"> <!-- Ensure correct path to CSS -->
</head>
<body>
    <!-- Login Form Section -->
    <div class="login-container">
        <div class="login-form">
            <h2>User Login</h2>
            <!-- Error Message Display -->
            <?php if (isset($error)) { echo "<div class='error-message'>$error</div>"; } ?>
            <form method="POST" action="login.php">
                <div class="input-group">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" placeholder="Enter your username" required>
                </div>
                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" placeholder="Enter your password" required>
                </div>
                <button type="submit" name="login" class="btn">Login</button>
            </form>
        </div>
    </div>
</body>
</html>