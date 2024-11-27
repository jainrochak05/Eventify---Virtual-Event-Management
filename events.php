<?php
// Include the database connection
include 'db.php'; // Ensure this path points to your `db.php`

// Fetch all events from the database
$stmt = $conn->prepare("SELECT id, name, event_date, venue, description FROM events ORDER BY event_date ASC");
$stmt->execute();
$result = $stmt->get_result();

$events = [];
while ($row = $result->fetch_assoc()) {
    $events[] = $row;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events</title>
    <link rel="stylesheet" href="styles/user_dash.css"> <!-- For Navbar and Footer -->
    <link rel="stylesheet" href="styles/events.css"> <!-- Event-specific styles -->
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
    <div class="navbar-brand">Eventify</div>
            <ul class="navbar-menu">
            <li><a href="index.php">Home</a></li>
                <li><a href="events.php">Events</a></li>
                <li><a href="user/login.php">User Dashboard</a></li>
                <li><a href="admin/login.php">Admin Login</a></li>
            </ul>
        
    </nav>

    <!-- Page Title -->
    <div class="page-header">
        <h1>Explore Events</h1>
        <p>Find and book the latest events happening near you!</p>
    </div>

    <!-- Events Section -->
    <div class="events-container">
        <?php if (count($events) > 0): ?>
            <table class="events-table">
                <thead>
                    <tr>
                        <th>Event Name</th>
                        <th>Date</th>
                        <th>Venue</th>
                        <th>Description</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($events as $event): ?>
                        <tr>
                            <td><?= htmlspecialchars($event['name']) ?></td>
                            <td><?= htmlspecialchars(date('F, j, Y', strtotime($event['event_date']))) ?></td>
                            <td><?= htmlspecialchars($event['venue']) ?></td>
                            <td><?= htmlspecialchars($event['description']) ?></td>
                            <td>
                                <a href="php/book_ticket.php?event_id=<?= $event['id'] ?>" class="btn">Book Now</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-events">No events are currently available. Check back later!</p>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 Eventify. All rights reserved.</p>
    </footer>
</body>
</html>