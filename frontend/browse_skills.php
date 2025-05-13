<?php
session_start();
require_once("../backend/db.php");

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch all users except the logged-in one
$sql = "SELECT id, name, skills, bio FROM users WHERE id != ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Browse Skills | Skill Swap</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f8fa;
        }
        .user-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 8px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h2 class="mb-4">Browse Other Users</h2>

    <!-- Display alerts for swap request status -->
    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'sent'): ?>
        <div class="alert alert-success">Swap request sent successfully!</div>
    <?php elseif (isset($_GET['msg']) && $_GET['msg'] === 'already_sent'): ?>
        <div class="alert alert-warning">You already sent a request to this user.</div>
    <?php endif; ?>

    <?php
    if ($result->num_rows > 0):
        while ($row = $result->fetch_assoc()):
    ?>
        <div class="user-card">
            <h5><?php echo htmlspecialchars($row['name']); ?></h5>
            <p><strong>Skills:</strong> <?php echo htmlspecialchars($row['skills']); ?></p>
            <p><strong>Bio:</strong> <?php echo htmlspecialchars($row['bio']); ?></p>

            <!-- Send Swap Request button -->
            <form method="POST" action="../backend/send_request.php" class="d-inline">
                <input type="hidden" name="receiver_id" value="<?php echo $row['id']; ?>">
                <button type="submit" class="btn btn-success btn-sm">Send Swap Request</button>
            </form>

            <!-- Send Message button -->
            <a href="send_message.php?to=<?php echo $row['id']; ?>" class="btn btn-outline-primary btn-sm ms-2">Send Message</a>
        </div>
    <?php
        endwhile;
    else:
        echo "<p>No other users found.</p>";
    endif;
    ?>
    
    <a href="dashboard.php" class="btn btn-secondary mt-4">⬅ Back to Dashboard</a>
</div>

</body>
</html>
