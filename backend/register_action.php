<?php
include 'db.php';

// Form se data lein
$name = $_POST['name'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Secure password
$skills = $_POST['skills'];
$bio = $_POST['bio'];

// Email already exist check
$check = $conn->prepare("SELECT id FROM users WHERE email = ?");
$check->bind_param("s", $email);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    echo "❌ This email is already registered.";
} else {
    // Insert user data
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, skills, bio) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $email, $password, $skills, $bio);

    if ($stmt->execute()) {
        echo "✅ Registration successful! <a href='../frontend/login.php'>Login here</a>";
    } else {
        echo "❌ Something went wrong. Please try again.";
    }

    $stmt->close();
}

$check->close();
$conn->close();
?>
