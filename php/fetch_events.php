<?php
include 'db.php';

// Fetch events from database
$events_query = "SELECT * FROM events";
$events_result = $conn->query($events_query);

if ($events_result->num_rows > 0) {
    while ($event = $events_result->fetch_assoc()) {
        echo '
            <div class="event-card">
                <h3>' . htmlspecialchars($event['name']) . '</h3>
                <p><strong>Date:</strong> ' . htmlspecialchars($event['date'] ?? 'Not specified') . '</p>
                <p><strong>Location:</strong> ' . htmlspecialchars($event['location'] ?? 'Not specified') . '</p>
                <p><strong>Description:</strong> ' . htmlspecialchars($event['description'] ?? 'No description available') . '</p>
                <p><strong>Price:</strong> $' . htmlspecialchars($event['price'] ?? '0.00') . '</p>
                <button class="book-btn" data-event-id="' . $event['id'] . '">Book Ticket</button>
            </div>';
    }
} else {
    echo '<p>No events available at the moment.</p>';
}
?>
