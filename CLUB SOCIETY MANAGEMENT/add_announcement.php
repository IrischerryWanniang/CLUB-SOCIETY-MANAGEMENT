<?php
session_start();
include 'db.php';

// Allow only admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    if (!empty($title) && !empty($content)) {
        $stmt = $conn->prepare("INSERT INTO announcements (title, content, created_at) VALUES (?, ?, NOW())");
        $stmt->bind_param("ss", $title, $content);

        if ($stmt->execute()) {
            echo "<script>alert('Announcement added successfully'); window.location='manage_announcements.php';</script>";
        } else {
            echo "<script>alert('Error adding announcement');</script>";
        }
    } else {
        echo "<script>alert('All fields are required.');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Announcement</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Add New Announcement</h2>
    <form method="POST">
        <input type="text" name="title" placeholder="Announcement Title" required><br>
        <textarea name="content" placeholder="Announcement Content" required></textarea><br>
        <button type="submit">Add Announcement</button>
    </form>
    <br>
    <a href="dashboard.php">Back to Dashboard</a>
</div>
</body>
</html>
