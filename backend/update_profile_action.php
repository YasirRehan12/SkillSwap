<?php
require_once("db.php");
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../frontend/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get form data
$name = $_POST['name'];
$email = $_POST['email'];
$skills = $_POST['skills'];
$bio = $_POST['bio'];

// Update query
$sql = "UPDATE users SET name = ?, email = ?, skills = ?, bio = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssi", $name, $email, $skills, $bio, $user_id);

if ($stmt->execute()) {
    header("Location: ../frontend/edit_profile.php?success=1");
    exit();
} else {
    header("Location: ../frontend/edit_profile.php?error=1");
    exit();
}
?>
