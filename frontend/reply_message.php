<?php
session_start();
require_once("../backend/db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$sender_id = $_SESSION['user_id'];
$receiver_id = isset($_GET['to']) ? intval($_GET['to']) : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = trim($_POST['message']);

    if (!empty($message)) {
        $sql = "INSERT INTO messages (sender_id, receiver_id, message, sent_at) VALUES (?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iis", $sender_id, $receiver_id, $message);
        if ($stmt->execute()) {
            header("Location: view_messages.php?msg=replied");
            exit();
        } else {
            $error = "Failed to send reply.";
        }
    } else {
        $error = "Message cannot be empty.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reply Message</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h3>Reply to Message</h3>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    <form method="POST">
        <div class="mb-3">
            <label for="message" class="form-label">Your Reply</label>
            <textarea name="message" id="message" rows="4" class="form-control" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Send Reply</button>
        <a href="view_messages.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>
