<?php
// Include the database connection
include '../db.php'; // Ensure this path points to your `db.php`

// Start session to validate user
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../user/login.php'); // Redirect to login if session not set
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user information
$stmt_user = $conn->prepare("SELECT username FROM users WHERE id = ?");
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$result_user = $stmt_user->get_result();
$user = $result_user->fetch_assoc();
$stmt_user->close();

if (!$user) {
    echo "Invalid session. Please log in again.";
    exit;
}

$username = htmlspecialchars($user['username']);

// Fetch all tickets for the user with event details
$stmt = $conn->prepare("SELECT 
                            tickets.ticket_code, 
                            events.name, 
                            events.event_date, 
                            events.venue, 
                            tickets.status 
                        FROM tickets 
                        JOIN events ON tickets.event_id = events.id 
                        WHERE tickets.user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$tickets = [];
while ($row = $result->fetch_assoc()) {
    $tickets[] = $row;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="../styles/user_dash.css"> <!-- Link to external CSS -->
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-brand">Eventify</div>
        <ul class="navbar-menu">
            <li><a href="../book_ticket.php">Book Event</a></li>
            <li><a href="../events.php">View Events</a></li>
            <li><a href="../logout.php">Logout</a></li>
        </ul>
    </nav>

    <!-- Greeting Section -->
    <section class="hero">
        <div class="hero-text">
            <h1>Welcome back, <?= $username ?>!</h1>
            <p>Here’s a summary of your booked tickets. Keep exploring and enjoy your events!</p>
        </div>
    </section>

    <!-- Ticket Section -->
    <section class="tickets">
        <div class="container">
            <h2>Your Tickets</h2>
            <?php if (count($tickets) > 0): ?>
                <table class="ticket-table">
                    <thead>
                        <tr>
                            <th>Event Name</th>
                            <th>Date</th>
                            <th>Venue</th>
                            <th>Ticket Code</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tickets as $ticket): ?>
                            <tr>
                                <td><?= htmlspecialchars($ticket['name']) ?></td>
                                <td><?= htmlspecialchars(date('F j, Y', strtotime($ticket['event_date']))) ?></td>
                                <td><?= htmlspecialchars($ticket['venue']) ?></td>
                                <td><?= htmlspecialchars($ticket['ticket_code']) ?></td>
                                <td>
                                    <?php
                                    $status = htmlspecialchars($ticket['status']);
                                    if ($status === 'Attended') {
                                        echo '<span class="status attended">Attended</span>';
                                    } elseif ($status === 'Not Attended') {
                                        echo '<span class="status not-attended">Not Attended</span>';
                                    } else {
                                        echo '<span class="status pending">Pending</span>';
                                    }
                                    ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="no-tickets">You haven’t booked any tickets yet. Start by <a href="../events.php">exploring events</a>.</p>
            <?php endif; ?>
        </div>
    </section>

    <footer>
        <p>&copy; 2024 Eventify. All rights reserved.</p>
    </footer>
</body>
</html>