<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch all clubs for the dropdown
$clubs_result = $conn->query("SELECT id, name FROM clubs");

// Handle new event submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $event_date = $_POST['event_date'];
    $club_id = intval($_POST['club_id']); // From dropdown

    if (!empty($title) && !empty($description) && !empty($event_date) && $club_id > 0) {
        $stmt = $conn->prepare("INSERT INTO events (club_id, title, description, event_date) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $club_id, $title, $description, $event_date);
        if ($stmt->execute()) {
            echo "<script>alert('Event added successfully'); window.location.href='view_event.php';</script>";
            exit();
        } else {
            echo "<script>alert('Error adding event');</script>";
        }
    } else {
        echo "<script>alert('All fields are required');</script>";
    }
}

// Fetch events
$sql = "SELECT events.id, events.title, events.description, events.event_date, clubs.name AS club_name 
        FROM events 
        JOIN clubs ON events.club_id = clubs.id 
        ORDER BY events.event_date ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Upcoming Events</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Upcoming Events</h2>
    <?php if ($result->num_rows > 0): ?>
        <ul>
            <?php while ($event = $result->fetch_assoc()): ?>
                <li>
                    <h3><?php echo htmlspecialchars($event['title']); ?> (<?php echo htmlspecialchars($event['club_name']); ?>)</h3>
                    <p><?php echo htmlspecialchars($event['description']); ?></p>
                    <p><strong>Date:</strong> <?php echo $event['event_date']; ?></p>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p>No events found.</p>
    <?php endif; ?>

    <!-- Add Event Form -->
    <h3>Add New Event</h3>
    <form method="post" action="">
        <input type="text" name="title" placeholder="Event Title" required><br>
        <input type="date" name="event_date" required><br>
        <textarea name="description" placeholder="Event Description" required></textarea><br>

        <!-- Club dropdown -->
        <select name="club_id" required>
            <option value="">Select Club</option>
            <?php while ($club = $clubs_result->fetch_assoc()): ?>
                <option value="<?php echo $club['id']; ?>"><?php echo htmlspecialchars($club['name']); ?></option>
            <?php endwhile; ?>
        </select><br>

        <input type="submit" name="submit" value="Add Event">
    </form>

    <br>
    <a href="dashboard.php">Back to Dashboard</a>
</div>
</body>
</html>


