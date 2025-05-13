<?php
session_start();
require_once("../backend/db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Check if 'with' user ID is passed
if (!isset($_GET['with']) || empty($_GET['with'])) {
    echo "User not specified.";
    exit();
}

$other_user_id = intval($_GET['with']);

// Fetch the name of the other user
$name_sql = "SELECT name FROM users WHERE id = ?";
$name_stmt = $conn->prepare($name_sql);
$name_stmt->bind_param("i", $other_user_id);
$name_stmt->execute();
$name_result = $name_stmt->get_result();
if ($name_result->num_rows === 0) {
    echo "User not found.";
    exit();
}
$other_user = $name_result->fetch_assoc();
$other_user_name = $other_user['name'];

// Handle reply submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    $message_text = trim($_POST['message']);
    if (!empty($message_text)) {
        $insert_sql = "INSERT INTO messages (sender_id, receiver_id, message, timestamp) VALUES (?, ?, ?, NOW())";
        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param("iis", $user_id, $other_user_id, $message_text);
        $stmt->execute();
    } else {
        $error = "Message cannot be empty.";
    }
}

// Retrieve messages
$sql = "SELECT messages.*, users.name AS sender_name 
        FROM messages 
        JOIN users ON messages.sender_id = users.id 
        WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?)
        ORDER BY timestamp ASC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iiii", $user_id, $other_user_id, $other_user_id, $user_id);
$stmt->execute();
$messages = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Conversation with <?php echo htmlspecialchars($other_user_name); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h3>Conversation with <?php echo htmlspecialchars($other_user_name); ?></h3>
    <a href="view_messages.php" class="btn btn-secondary mb-3">← Back to Messages</a>

    <div class="border rounded p-3 mb-4 bg-white" style="height: 300px; overflow-y: auto;">
        <?php while ($row = $messages->fetch_assoc()): ?>
            <div class="mb-2">
                <strong><?php echo htmlspecialchars($row['sender_name']); ?>:</strong>
                <?php echo htmlspecialchars($row['message']); ?>
                <small class="text-muted d-block"><?php echo $row['timestamp']; ?></small>
            </div>
        <?php endwhile; ?>
    </div>

    <form method="POST">
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <div class="mb-3">
            <textarea name="message" class="form-control" placeholder="Type your message here..." required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Send Reply</button>
    </form>
</div>
</body>
</html>
