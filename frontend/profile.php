<?php
session_start();
require_once("../backend/db.php");

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get logged-in user ID
$user_id = $_SESSION['user_id'];

// Fetch user data from database
$sql = "SELECT name, email, skills, bio FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
} else {
    echo "User not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Profile | Skill Swap</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f5f8fa;
    }
    .profile-box {
      max-width: 700px;
      margin: 50px auto;
      background: white;
      padding: 40px;
      border-radius: 12px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    .profile-box h2 {
      margin-bottom: 20px;
    }
  </style>
</head>
<body>

<div class="container">
  <div class="profile-box">
    <h2>👤 My Profile</h2>
    <p><strong>Name:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
    <p><strong>Skills:</strong> <?php echo htmlspecialchars($user['skills']); ?></p>
    <p><strong>Bio:</strong> <?php echo nl2br(htmlspecialchars($user['bio'])); ?></p>

    <a href="dashboard.php" class="btn btn-secondary mt-4">⬅ Back to Dashboard</a>
  </div>
</div>

</body>
</html>
