<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Handle event creation
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $club_id = $_POST['club_id'];
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $event_date = $_POST['event_date'];

    $stmt = $conn->prepare("INSERT INTO events (club_id, title, description, event_date) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $club_id, $title, $description, $event_date);

    if ($stmt->execute()) {
        echo "<script>alert('Event created successfully!'); window.location='manage_events.php';</script>";
    } else {
        echo "<script>alert('Error creating event.');</script>";
    }
}

// Get clubs for event selection
$sql = "SELECT id, name FROM clubs";
$clubs_result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Events</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Manage Events</h2>
    <form method="post" action="manage_events.php">
        <label for="club_id">Select Club:</label>
        <select name="club_id" required>
            <?php while ($club = $clubs_result->fetch_assoc()) { ?>
                <option value="<?php echo $club['id']; ?>"><?php echo $club['name']; ?></option>
            <?php } ?>
        </select><br>
        
        <label for="title">Event Title:</label>
        <input type="text" name="title" required><br>

        <label for="description">Event Description:</label>
        <textarea name="description" required></textarea><br>

        <label for="event_date">Event Date:</label>
        <input type="date" name="event_date" required><br>

        <button type="submit">Create Event</button>
    </form>
</div>
</body>
</html>



