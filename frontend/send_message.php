<?php
session_start();
require_once("../backend/db.php");

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get sender and receiver IDs
$sender_id = $_SESSION['user_id'];
$receiver_id = isset($_GET['to']) ? intval($_GET['to']) : 0;

// Prevent sending messages to self
if ($sender_id === $receiver_id || $receiver_id === 0) {
    echo "<p>Invalid recipient.</p>";
    exit();
}

// Fetch receiver's name
$sql = "SELECT name FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $receiver_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    echo "<p>User not found.</p>";
    exit();
}

$receiver = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Send Message | Skill Swap</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
        }
        .message-box {
            max-width: 600px;
            margin: 50px auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
<div class="container">
    <div class="message-box">
        <h3>Send Message to <?php echo htmlspecialchars($receiver['name']); ?></h3>
        <form method="POST" action="../backend/process_message.php">
            <input type="hidden" name="receiver_id" value="<?php echo $receiver_id; ?>">
            <div class="mb-3">
                <label for="message" class="form-label">Message</label>
                <textarea name="message" id="message" class="form-control" rows="5" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Send</button>
            <a href="browse_skills.php" class="btn btn-secondary">Back</a>
        </form>
    </div>
</div>
</body>
</html>
