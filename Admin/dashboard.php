<?php
session_start();
include '../db.php';

// Ensure admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit;
}

// Handle ticket status update
if (isset($_POST['update_ticket'])) {
    $ticket_code = $_POST['ticket_code'] ?? '';
    if (!empty($ticket_code)) {
        $stmt = $conn->prepare("UPDATE tickets SET status = 'Attended' WHERE ticket_code = ?");
        $stmt->bind_param("s", $ticket_code);
        if ($stmt->execute()) {
            $update_message = "Ticket status updated successfully!";
        } else {
            $update_error = "Failed to update ticket status.";
        }
        $stmt->close();
    } else {
        $update_error = "Please provide a valid ticket code.";
    }
}

// Handle ticket deletion
if (isset($_POST['delete_ticket'])) {
    $ticket_code = $_POST['ticket_code'] ?? '';
    if (!empty($ticket_code)) {
        $stmt = $conn->prepare("DELETE FROM tickets WHERE ticket_code = ?");
        $stmt->bind_param("s", $ticket_code);
        if ($stmt->execute()) {
            $delete_message = "Ticket deleted successfully!";
        } else {
            $delete_error = "Failed to delete ticket.";
        }
        $stmt->close();
    } else {
        $delete_error = "Please provide a valid ticket code.";
    }
}

// Handle ticket addition
if (isset($_POST['add_ticket'])) {
    $user_id = $_POST['user_id'] ?? '';
    $event_id = $_POST['event_id'] ?? '';
    if (!empty($user_id) && !empty($event_id)) {
        $ticket_code = "TCK" . rand(10000, 99999); // Generate a unique ticket code
        $stmt = $conn->prepare("INSERT INTO tickets (user_id, event_id, ticket_code) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $user_id, $event_id, $ticket_code);
        if ($stmt->execute()) {
            $add_message = "Ticket added successfully!";
        } else {
            $add_error = "Failed to add ticket.";
        }
        $stmt->close();
    } else {
        $add_error = "Please provide valid user ID and event ID.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    
    <link rel="stylesheet" href="../styles/admin.css">
    
</head>
<body>
    <!-- Navbar -->
    <header class="header">
        <div class="logo">Eventify</div>
    
<nav class="navbar">
    
    <ul class="navbar-menu">
        <li><a href="view_users.php">View All Users</a></li>
        <li><a href="view_tickets.php">View All Tickets</a></li>
        <li><a href="../logout.php">Logout</a></li>
    </ul>
</nav>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-text">
            <h1>Welcome, Admin!</h1>
            <p>Manage your tickets with ease and efficiency.</p>
        </div>
    </section>

    <!-- Update Ticket Status Section -->
    <section class="form-section">
        <h2>Update Ticket Status</h2>
        <?php if (isset($update_message)) echo "<p class='success'>$update_message</p>"; ?>
        <?php if (isset($update_error)) echo "<p class='error'>$update_error</p>"; ?>
        <form method="POST" action="">
            <input type="text" name="ticket_code" placeholder="Enter Ticket Code" required>
            <button type="submit" name="update_ticket" class="btn">Check In Guest</button>
        </form>
    </section>

    <!-- Delete Ticket Section -->
    <section class="form-section">
        <h2>Delete Ticket</h2>
        <?php if (isset($delete_message)) echo "<p class='success'>$delete_message</p>"; ?>
        <?php if (isset($delete_error)) echo "<p class='error'>$delete_error</p>"; ?>
        <form method="POST" action="">
            <input type="text" name="ticket_code" placeholder="Enter Ticket Code" required>
            <button type="submit" name="delete_ticket" class="btn">Delete Ticket</button>
        </form>
    </section>

    <!-- Add Ticket Section -->
    <section class="form-section">
        <h2>Add Ticket</h2>
        <?php if (isset($add_message)) echo "<p class='success'>$add_message</p>"; ?>
        <?php if (isset($add_error)) echo "<p class='error'>$add_error</p>"; ?>
        <form method="POST" action="">
            <input type="number" name="user_id" placeholder="Enter User ID" required>
            <input type="number" name="event_id" placeholder="Enter Event ID" required>
            <button type="submit" name="add_ticket" class="btn">Add Ticket</button>
        </form>
    </section>

    <footer>
        <p>&copy; 2024 Eventify. All rights reserved.</p>
    </footer>
</body>
</html>