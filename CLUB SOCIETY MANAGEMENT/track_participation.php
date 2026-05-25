<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Handle delete
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $event_id = $_GET['event'];
    $stmt = $conn->prepare("DELETE FROM participation WHERE id = ? AND event_id = ?");
    $stmt->bind_param("si", $delete_id, $event_id);
    $stmt->execute();
    header("Location: track_participation.php");
    exit();
}

// Fetch all participation records
$sql = "SELECT p.id AS student_id, p.event_id, e.title AS event_title, p.status 
        FROM participation p
        JOIN events e ON p.event_id = e.id
        ORDER BY e.title ASC, p.id ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Participation Records</title>
    <style>
        table { border-collapse: collapse; width: 90%; margin: auto; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: center; }
        th { background-color: #f2f2f2; }
        a.btn { padding: 4px 8px; text-decoration: none; margin: 2px; display: inline-block; }
        .edit { background: #f0ad4e; color: white; }
        .delete { background: #d9534f; color: white; }
    </style>
</head>
<body>
    <h2 style="text-align:center;">Participation Records</h2>
    <table>
        <tr>
            <th>Student ID</th>
            <th>Event Title</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['student_id']) ?></td>
                    <td><?= htmlspecialchars($row['event_title']) ?></td>
                    <td><?= htmlspecialchars($row['status']) ?></td>
                    <td>
                        <a class="btn delete" href="?delete=<?= $row['student_id'] ?>&event=<?= $row['event_id'] ?>" onclick="return confirm('Are you sure?');">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="4">No participation records found.</td></tr>
        <?php endif; ?>
    </table>

    <br><div style="text-align:center;"><a href="dashboard.php">← Back to Dashboard</a></div>
</body>
</html>
