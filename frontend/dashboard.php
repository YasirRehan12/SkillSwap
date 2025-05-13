<?php
session_start();
require_once("../backend/db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch user's name from database
$user_id = $_SESSION['user_id'];
$sql = "SELECT name FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$name = $user['name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard | Skill Swap</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #f2f4f7, #dfe9f3);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .dashboard-card {
            max-width: 600px;
            margin: 80px auto;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            background-color: #ffffff;
        }
        .dashboard-card h2 {
            font-weight: bold;
        }
        .btn-custom {
            padding: 12px;
            font-size: 16px;
            margin-bottom: 12px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="dashboard-card text-center">
        <h2 class="mb-3">Skill Swap Dashboard</h2>
        <p class="lead">👋 Welcome, <strong><?php echo htmlspecialchars($name); ?></strong>!</p>
        <hr class="mb-4">

        <a href="edit_profile.php" class="btn btn-primary btn-custom w-100">✏️ Edit Profile</a>
        <a href="browse_skills.php" class="btn btn-success btn-custom w-100">🔍 Browse Skills</a>
        <a href="view_requests.php" class="btn btn-warning btn-custom w-100">📥 View Swap Requests</a>
        <a href="view_messages.php" class="btn btn-dark w-100 mb-3">📨 View Messages</a>


        <a href="../backend/logout.php" class="btn btn-danger btn-custom w-100">🚪 Logout</a>

    </div>
</div>

</body>
</html>
