<?php
session_start();
include 'db.php';

// Only allow admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Fetch available clubs
$clubs_result = $conn->query("SELECT id, name FROM clubs");

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $club_id = intval($_POST['club_id']);
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $event_date = $_POST['event_date'];

    if (!empty($club_id) && !empty($title) && !empty($description) && !empty($event_date)) {
        $stmt = $conn->prepare("INSERT INTO events (club_id, title, description, event_date) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $club_id, $title, $description, $event_date);

        if ($stmt->execute()) {
            echo "<script>alert('Event added successfully'); window.location='manage_event.php';</script>";
        } else {
            echo "<script>alert('Error adding event');</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('All fields are required.');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Event</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Add New Event</h2>
    <form method="POST" action="add_event.php">
        <label for="club_id">Select Club:</label><br>
        <select name="club_id" id="club_id" required>
            <option value="">-- Select Club --</option>
            <?php while ($club = $clubs_result->fetch_assoc()) { ?>
                <option value="<?php echo $club['id']; ?>"><?php echo htmlspecialchars($club['name']); ?></option>
            <?php } ?>
        </select><br><br>

        <input type="text" name="title" placeholder="Event Title" required><br>
        <textarea name="description" placeholder="Event Description" required></textarea><br>
        <input type="date" name="event_date" required><br>
        <button type="submit">Add Event</button>
    </form>
    <br>
    <a href="dashboard.php">Back to Dashboard</a>
</div>
</body>
</html>
