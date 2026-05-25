<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Handle announcement submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $title = trim($_POST['title']);
    $content = trim($_POST['message']);
    $date = $_POST['announcement_date'];
    $time = $_POST['announcement_time'];
    $created_at = $date . ' ' . $time;

    if (!empty($title) && !empty($content) && !empty($date) && !empty($time)) {
        $stmt = $conn->prepare("INSERT INTO announcements (title, content, created_at) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $title, $content, $created_at);

        if ($stmt->execute()) {
            echo "<script>alert('Announcement posted successfully'); window.location.href='view_announcement.php';</script>";
            exit();
        } else {
            echo "<script>alert('Failed to post announcement');</script>";
        }
    } else {
        echo "<script>alert('All fields are required');</script>";
    }
}

// Fetch announcements
$result = $conn->query("SELECT title, content, created_at FROM announcements ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Announcements</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Announcements</h2>
    <?php if ($result->num_rows > 0) { ?>
        <ul>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <li>
                    <strong><?php echo htmlspecialchars($row['title']); ?></strong><br>
                    <?php echo nl2br(htmlspecialchars($row['content'])); ?><br>
                    <small><?php echo $row['created_at']; ?></small>
                </li>
                <hr>
            <?php } ?>
        </ul>
    <?php } else { ?>
        <p>No announcements available.</p>
    <?php } ?>
    <hr>

    <h3>Post New Announcement</h3>
    <form method="post" action="">
        <input type="text" name="title" placeholder="Announcement Title" required><br>
        <input type="date" name="announcement_date" required><br>
        <input type="time" name="announcement_time" required><br>
        <textarea name="message" placeholder="Announcement Message" required></textarea><br>
        <input type="submit" name="submit" value="Post Announcement">
    </form>

    <br>
    <a href="dashboard.php">Back to Dashboard</a>
</div>
</body>
</html>




