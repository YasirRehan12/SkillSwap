<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register | Skill Swap</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f2f2f2;
    }
    .register-box {
      max-width: 500px;
      margin: 50px auto;
      background-color: white;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    .form-label {
      font-weight: bold;
    }
  </style>
</head>
<body>

<div class="container">
  <div class="register-box">
    <h3 class="text-center mb-4">Create Your Skill Swap Account</h3>

    <form action="../backend/register_action.php" method="POST">
      <div class="mb-3">
        <label for="name" class="form-label">Full Name</label>
        <input type="text" class="form-control" id="name" name="name" required>
      </div>

      <div class="mb-3">
        <label for="email" class="form-label">Email address</label>
        <input type="email" class="form-control" id="email" name="email" required>
      </div>

      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password" required>
      </div>

      <div class="mb-3">
        <label for="skills" class="form-label">Your Skills</label>
        <textarea class="form-control" id="skills" name="skills" rows="2" required></textarea>
      </div>

      <div class="mb-3">
        <label for="bio" class="form-label">Short Bio</label>
        <textarea class="form-control" id="bio" name="bio" rows="3"></textarea>
      </div>

      <button type="submit" class="btn btn-primary w-100">Register</button>
    </form>

    <p class="text-center mt-3">Already have an account? <a href="login.php">Login</a></p>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
