<?php
session_start();
include 'db.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Handle club addition
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_club'])) {
    $club_name = trim($_POST['club_name']);
    $club_description = trim($_POST['club_description']);

    if (!empty($club_name) && !empty($club_description)) {
        $stmt = $conn->prepare("INSERT INTO clubs (name, description) VALUES (?, ?)");
        $stmt->bind_param("ss", $club_name, $club_description);
        if ($stmt->execute()) {
            echo "<script>alert('Club added successfully'); window.location.href='view_club.php';</script>";
            exit();
        } else {
            echo "<script>alert('Error adding club');</script>";
        }
    } else {
        echo "<script>alert('Both fields are required');</script>";
    }
}

// Fetch clubs from database
$sql = "SELECT id, name, description FROM clubs ORDER BY name ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Clubs</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>All Clubs</h2>
    <?php if ($result->num_rows > 0): ?>
        <ul>
            <?php while ($club = $result->fetch_assoc()): ?>
                <li>
                    <h3><?php echo htmlspecialchars($club['name']); ?></h3>
                    <p><?php echo htmlspecialchars($club['description']); ?></p>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p>No clubs found.</p>
    <?php endif; ?>

    <!-- Add Club Form -->
    <h3>Add a New Club</h3>
    <form method="POST" action="">
        <input type="text" name="club_name" placeholder="Club Name" required><br>
        <textarea name="club_description" placeholder="Club Description" required></textarea><br>
        <button type="submit" name="add_club">Add Club</button>
    </form>

    <br>
    <a href="dashboard.php">Back to Dashboard</a>
</div>
</body>
</html>
