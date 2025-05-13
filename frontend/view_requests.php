<?php
session_start();
require_once("../backend/db.php");

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch requests where logged-in user is the receiver
$sql = "SELECT sr.id, sr.sender_id, sr.status, u.name AS sender_name, u.email AS sender_email
        FROM swap_requests sr
        JOIN users u ON sr.sender_id = u.id
        WHERE sr.receiver_id = ?
        ORDER BY sr.created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View Requests | Skill Swap</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
  <h2 class="mb-4">Incoming Swap Requests</h2>

  <!-- Messages -->
  <?php if (isset($_GET['msg']) && $_GET['msg'] === 'accepted'): ?>
      <div class="alert alert-success">Request accepted successfully!</div>
  <?php elseif (isset($_GET['msg']) && $_GET['msg'] === 'rejected'): ?>
      <div class="alert alert-warning">Request rejected.</div>
  <?php endif; ?>

  <?php if ($result->num_rows > 0): ?>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Sender Name</th>
          <th>Email</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?php echo htmlspecialchars($row['sender_name']); ?></td>
            <td><?php echo htmlspecialchars($row['sender_email']); ?></td>
            <td><?php echo ucfirst($row['status']); ?></td>
            <td>
              <?php if ($row['status'] === 'pending'): ?>
                <a href="../backend/accept_request.php?request_id=<?php echo $row['id']; ?>" class="btn btn-success btn-sm">Accept</a>
                <a href="../backend/reject_request.php?request_id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm">Reject</a>
              <?php else: ?>
                <span class="text-muted">No action needed</span>
              <?php endif; ?>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  <?php else: ?>
    <div class="alert alert-info">No requests found.</div>
  <?php endif; ?>

  <a href="dashboard.php" class="btn btn-secondary mt-3">⬅ Back to Dashboard</a>
</div>
</body>
</html>
