<?php
session_start();
include 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Logout logic
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Welcome, <?php echo $_SESSION['username']; ?></h2>

        <?php if ($role == 'admin') { ?>
            <h4>Admin Dashboard</h4>
            <ul>
                <li><a href="manage_club.php">Manage Clubs</a></li>
                <li><a href="manage_event.php">Manage Events</a></li>
                <li><a href="manage_announcement.php">Manage Announcements</a></li>
                <li><a href="manage_participation.php">Manage Participation</a></li>
                <!--<li><a href="track_participation.php"> Track Participation</a></li>-->
            </ul>
        <?php } else { ?>
            <h4>Student Dashboard</h4>
            <ul>
                <li><a href="view_club.php">View Clubs</a></li>
                <li><a href="view_event.php">View Events</a></li>
                <li><a href="view_announcement.php">View Announcements</a></li>
                <li><a href="view_participation.php">View Participation</a></li>
                <li><a href="track_participation.php"> Track Participation</a></li>

            </ul>
        <?php } ?>

        <form method="POST">
            <button type="submit" name="logout">Logout</button>
        </form>
    </div>
</body>
</html>

