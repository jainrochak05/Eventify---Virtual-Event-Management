<?php
session_start();
include '../db.php';

// Ensure admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit;
}

// Fetch all tickets with user and event details
$query = "SELECT 
            tickets.ticket_code, 
            tickets.status, 
            users.username AS user_name, 
            events.name AS event_name, 
            events.event_date 
          FROM tickets
          JOIN users ON tickets.user_id = users.id
          JOIN events ON tickets.event_id = events.id";
$result = $conn->query($query);

if (!$result) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Tickets</title>
    <link rel="stylesheet" href="../styles/admin.css">
</head>
<body>
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

    <!-- All Tickets Section -->
    <section class="form-section">
        <h2>All Tickets</h2>
        <table class="ticket-table">
            <thead>
                <tr>
                    <th>Ticket Code</th>
                    <th>User</th>
                    <th>Event</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['ticket_code']) ?></td>
                        <td><?= htmlspecialchars($row['user_name']) ?></td>
                        <td><?= htmlspecialchars($row['event_name']) ?></td>
                        <td><?= htmlspecialchars(date('F j, Y', strtotime($row['event_date']))) ?></td>
                        <td><?= htmlspecialchars($row['status']) ?></td>
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