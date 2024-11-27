<?php
session_start();
include '../db.php';

// Ensure admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit;
}

// Fetch all users
$result = $conn->query("SELECT id, username, name , l_name , age FROM users");

if (!$result) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Users</title>
    <link rel="stylesheet" href="../styles/admin.css">
</head>
<body>
    <!-- Navbar -->
    <header class="header">
        <div class="logo">Eventify</div>
    
<nav class="navbar">
    
    <ul class="navbar-menu">
    <li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="view_users.php">View All Users</a></li>
        <li><a href="view_tickets.php">View All Tickets</a></li>
        <li><a href="../logout.php">Logout</a></li>
    </ul>
</nav>
    </header>
   

    <!-- All Users Section -->
    <section class="form-section">
        <h2>All Registered Users</h2>
        <table class="ticket-table">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Username</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Age</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id']) ?></td>
                        <td><?= htmlspecialchars($row['username']) ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['l_name']) ?></td>
                        <td><?= htmlspecialchars($row['age']) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </section>
    <footer>
        <p>&copy; 2024 Eventify. All rights reserved.</p>
    </footer>
</body>
</html>