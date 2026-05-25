<?php
session_start();
include 'db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Add new club
    $club_name = $_POST['name'];
    $club_description = $_POST['description'];

    $stmt = $conn->prepare("INSERT INTO clubs (name, description) VALUES (?, ?)");
    $stmt->bind_param("ss", $club_name, $club_description);
    if($stmt->execute()){
      echo "New club added!";
    }else{
      echo "error: ". $stmt->error;
    }
   $stmt->close();
}
 $conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Clubs</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Add Clubs</h2>
        <form method="POST">
            <input type="text" id="name" name="name" placeholder="Club Name" required><br>
            <textarea name="description" placeholder="Club Description" required></textarea><br>
            <button type="submit">Add Club</button>
        </form>
        <a href="dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>
