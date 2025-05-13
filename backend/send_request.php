<?php
session_start();
require_once("db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: ../frontend/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['receiver_id'])) {
    $sender_id = $_SESSION['user_id'];
    $receiver_id = $_POST['receiver_id'];

    // Check if same request already exists
    $check_sql = "SELECT id FROM swap_requests WHERE sender_id = ? AND receiver_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ii", $sender_id, $receiver_id);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        // Already sent
        header("Location: ../frontend/browse_skills.php?msg=already_sent");
        exit();
    }

    // Insert new request
    $sql = "INSERT INTO swap_requests (sender_id, receiver_id, status) VALUES (?, ?, 'pending')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $sender_id, $receiver_id);
    
    if ($stmt->execute()) {
        header("Location: ../frontend/browse_skills.php?msg=sent");
    } else {
        echo "Error sending request.";
    }
} else {
    header("Location: ../frontend/dashboard.php");
    exit();
}
?>
