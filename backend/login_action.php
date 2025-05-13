<?php
session_start();
include 'db.php';

$email = $_POST['email'];
$password = $_POST['password'];

// Check if email exists
$stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 1) {
    $stmt->bind_result($id, $name, $hashed_password);
    $stmt->fetch();

    if (password_verify($password, $hashed_password)) {
        // Password match - login successful
        $_SESSION['user_id'] = $id;
        $_SESSION['user_name'] = $name;

        header("Location: ../frontend/dashboard.php");
        exit();
    } else {
        echo "❌ Incorrect password.";
    }
} else {
    echo "❌ Email not found.";
}

$stmt->close();
$conn->close();
?>
