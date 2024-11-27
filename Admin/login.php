<?php
session_start();
if (isset($_POST['login'])) {
    // Hardcoded admin credentials (You should replace this with actual credentials from a database)
    $admin_username = 'admin';
    $admin_password = 'pw123';

    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username === $admin_username && $password === $admin_password) {
        // Set session and redirect to admin dashboard
        $_SESSION['admin_id'] = $username;
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Invalid credentials!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="../styles/admin_login.css">
</head>
<body>
    <!-- Admin Login Form -->
    <div class="login-container">
        <div class="login-form">
            <h2>Admin Login</h2>
            <?php if (isset($error)) { echo "<div class='error-message'>$error</div>"; } ?>
            <form method="POST" action="login.php">
                <div class="input-group">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" placeholder="Enter username" required>
                </div>
                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" placeholder="Enter password" required>
                </div>
                <button type="submit" name="login" class="btn">Login</button>
            </form>
        </div>
    </div>
</body>
</html>