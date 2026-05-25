<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Handle participation form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_participation'])) {
    $student_id = trim($_POST['id']); // Student ID
    $event_id = intval($_POST['event_id']);
    $status = $_POST['status'];

    // Validate input
    if (!empty($student_id) && $event_id > 0 && in_array($status, ['Attended', 'Absent'])) {

        // Check if the student exists in the users table
        $check_user = $conn->prepare("SELECT id FROM users WHERE id = ?");
        $check_user->bind_param("i", $student_id);
        $check_user->execute();
        $check_user_result = $check_user->get_result();

        // If student does not exist, show an error
        if ($check_user_result->num_rows == 0) {
            echo "<script>alert('This student ID does not exist in the system.');</script>";
        } else {
            // Check if the student is already participating in any event
            $check_participation = $conn->prepare("SELECT id FROM participation WHERE user_id = ?");
            $check_participation->bind_param("i", $student_id);
            $check_participation->execute();
            $check_participation_result = $check_participation->get_result();

            // If student is already participating in any event, show an alert
            if ($check_participation_result->num_rows > 0) {
                echo "<script>alert('This student is already participating in another event. A student can only participate in one event at a time.');</script>";
            } else {
                // Insert participation record if the student is not already in another event
                $stmt = $conn->prepare("INSERT INTO participation (user_id, event_id, status) VALUES (?, ?, ?)");
                if (!$stmt) {
                    die("Prepare failed: " . $conn->error);
                }

                // Bind parameters and insert the record
                $stmt->bind_param("iis", $student_id, $event_id, $status); // Bind the student_id, event_id, and status
                if ($stmt->execute()) {
                    $_SESSION['success_message'] = "✅ Participation recorded successfully!";
                    header("Location: view_participation.php");  // Redirect to view page after success
                    exit();
                } else {
                    die("Execute failed: " . $stmt->error);
                }
            }
        }
    } else {
        echo "<script>alert('Invalid input values');</script>";
    }
}

// Get events for dropdown
$events = $conn->query("SELECT id, title FROM events");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Participation</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Manage Participation</h2>
    
    <!-- Display success or error message -->
    <?php if (isset($_SESSION['success_message'])): ?>
        <p style="color: green;"><?= $_SESSION['success_message']; ?></p>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>

    <form method="POST" action="manage_participation.php">
        <!-- Input for student ID -->
        <label for="id">Enter Student ID:</label>
        <input type="text" id="id" name="id" placeholder="Enter Student ID" required><br><br>

        <!-- Dropdown for selecting event -->
        <label for="event_id">Event:</label>
        <select name="event_id" required>
            <option value="">Select Event</option>
            <?php while ($event = $events->fetch_assoc()): ?>
                <option value="<?= $event['id'] ?>"><?= htmlspecialchars($event['title']) ?></option>
            <?php endwhile; ?>
        </select><br><br>

        <!-- Dropdown for attendance status -->
        <label for="status">Status:</label>
        <select name="status" required>
            <option value="Attended">Attended</option>
            <option value="Absent">Absent</option>
        </select><br><br>

        <input type="submit" name="submit_participation" value="Record Participation">
    </form>

    <br><a href="dashboard.php">Back to Dashboard</a>
</div>
</body>
</html>
