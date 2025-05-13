<?php
session_start();
require_once("db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: ../frontend/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sender_id = $_SESSION['user_id'];
    $receiver_id = $_POST['receiver_id'];
    $message = trim($_POST['message']);

    if (empty($message)) {
        echo "Message cannot be empty.";
        exit();
    }

    $sql = "INSERT INTO messages (sender_id, receiver_id, message, timestamp) VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $sender_id, $receiver_id, $message);

    if ($stmt->execute()) {
        header("Location: ../frontend/conversation.php?with=" . $receiver_id);
    } else {
        echo "Error sending reply.";
    }
} else {
    header("Location: ../frontend/dashboard.php");
    exit();
}
?>
