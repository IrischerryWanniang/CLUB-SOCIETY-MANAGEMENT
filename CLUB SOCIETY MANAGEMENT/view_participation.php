<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Query to fetch all participation records
$sql = "SELECT p.id, e.title AS event_title, p.status 
        FROM participation p
        JOIN events e ON p.event_id = e.id";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Participation</title>
</head>
<body>
    <h2>View Participation Records</h2>

    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="success-message">
            <?= $_SESSION['success_message']; ?>
            <?php unset($_SESSION['success_message']); ?>
        </div>
    <?php endif; ?>

    <table border="1">
        <thead>
            <tr>
                <th>Student ID</th>
                <th>Event Title</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id']); ?></td>
                        <td><?= htmlspecialchars($row['event_title']); ?></td>
                        <td><?= htmlspecialchars($row['status']); ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3">No participation records found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <br><a href="dashboard.php">Back to Dashboard</a>
</body>
</html>
