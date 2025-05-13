<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once("../backend/db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Messages</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2 class="mb-4">📨 Your Messages</h2>

    <a href="dashboard.php" class="btn btn-secondary mb-3">← Back to Dashboard</a>

    <?php
    $sql = "SELECT messages.*, u1.name AS sender_name, u2.name AS receiver_name 
            FROM messages
            JOIN users u1 ON messages.sender_id = u1.id
            JOIN users u2 ON messages.receiver_id = u2.id
            WHERE messages.sender_id = ? OR messages.receiver_id = ?
            ORDER BY messages.timestamp DESC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0): ?>
        <div class="list-group">
            <?php while ($row = $result->fetch_assoc()): 
                $other_user_id = ($row['sender_id'] == $user_id) ? $row['receiver_id'] : $row['sender_id'];
                $other_user_name = ($row['sender_id'] == $user_id) ? $row['receiver_name'] : $row['sender_name'];
            ?>
                <a href="conversation.php?with=<?php echo $other_user_id; ?>" class="list-group-item list-group-item-action">
                    <strong><?php echo htmlspecialchars($other_user_name); ?></strong><br>
                    <small><?php echo htmlspecialchars($row['message']); ?> | <?php echo $row['timestamp']; ?></small>
                </a>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info">You have no messages yet.</div>
    <?php endif; ?>
</div>

</body>
</html>
