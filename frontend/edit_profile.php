<?php
session_start();
require_once("../backend/db.php");

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get user info
$user_id = $_SESSION['user_id'];
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
  <title>Edit Profile | Skill Swap</title>
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
    <h2>Edit Profile</h2>

    <!-- Success/Error Message -->
    <?php if (isset($_GET['success'])): ?>
      <div class="alert alert-success">Profile updated successfully!</div>
    <?php elseif (isset($_GET['error'])): ?>
      <div class="alert alert-danger">Something went wrong. Please try again.</div>
    <?php endif; ?>

    <!-- Profile edit form -->
    <form method="POST" action="../backend/update_profile_action.php">
      <div class="mb-3">
        <label for="name" class="form-label">Name</label>
        <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
      </div>
      
      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
      </div>
      
      <div class="mb-3">
        <label for="skills" class="form-label">Skills</label>
        <input type="text" class="form-control" id="skills" name="skills" value="<?= htmlspecialchars($user['skills']) ?>">
      </div>
      
      <div class="mb-3">
        <label for="bio" class="form-label">Bio</label>
        <textarea class="form-control" id="bio" name="bio" rows="3"><?= htmlspecialchars($user['bio']) ?></textarea>
      </div>

      <button type="submit" class="btn btn-primary">Update Profile</button>
    </form>

    <a href="dashboard.php" class="btn btn-secondary mt-4">⬅ Back to Dashboard</a>
  </div>
</div>

</body>
</html>
