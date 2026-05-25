<?php
session_start();
include 'db.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
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
    <a href="dashboard.php">Back to Dashboard</a>
</div>
</body>
</html>

