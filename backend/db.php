<?php
$host = "localhost";
$username = "root";
$password = ""; // XAMPP mein default password blank hota hai
$database = "skill_swap";

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
} else {
    echo "✅ Connected successfully to database.";
}

// Baad mein is test message ko hata dena
?>
