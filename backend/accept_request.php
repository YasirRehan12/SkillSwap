<?php
session_start();
require_once("db.php");

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../frontend/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$request_id = $_GET['request_id']; // Get request ID from URL

// Update request status to 'accepted' where current user is the receiver
$update_sql = "UPDATE swap_requests SET status = 'accepted' WHERE id = ? AND receiver_id = ?";
$stmt = $conn->prepare($update_sql);
$stmt->bind_param("ii", $request_id, $user_id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    header("Location: ../frontend/view_requests.php?msg=accepted");
} else {
    echo "Error accepting the request.";
}
?>
