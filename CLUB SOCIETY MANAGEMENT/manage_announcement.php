<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Handle announcement creation
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $message = trim($_POST['message']);

    $stmt = $conn->prepare("INSERT INTO announcements (title, message) VALUES (?, ?)");
    $stmt->bind_param("ss", $title, $message);

    if ($stmt->execute()) {
        echo "<script>alert('Announcement created successfully!'); window.location='manage_announcements.php';</script>";
    } else {
        echo "<script>alert('Error creating announcement.');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Announcements</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Manage Announcements</h2>
    <form method="post" action="manage_announcements.php">
        <label for="title">Announcement Title:</label>
        <input type="text" name="title" required><br>

        <label for="message">Announcement Message:</label>
        <textarea name="message" required></textarea><br>

        <button type="submit">Create Announcement</button>
    </form>
</div>
</body>
</html>


