<?php
session_start();
include 'includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $nameOrEmail = trim($_POST['username']);
  $password = trim($_POST['password']);

  // Find user by username or email
  $sql = "SELECT * FROM users WHERE username = ? OR email = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ss", $nameOrEmail, $nameOrEmail);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    // Verify password
    if (password_verify($password, $user['password'])) {
      $_SESSION['user_id'] = $user['user_id'];
      $_SESSION['username'] = $user['username'];
      $_SESSION['email'] = $user['email'];
      $_SESSION['role'] = $user['role'];

      // Redirect based on role
      if ($user['role'] === 'admin') {
        header("Location: admin/dashboard.php");
        exit();
      } else {
        header("Location: index.php");
        exit();
      }
    } else {
      $error = "❌ Invalid password.";
    }
  } else {
    $error = "❌ No user found with that username or email.";
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login - Crime Tracker</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="assets/css/login.css">
</head>
<body>
  <div class="auth-box">
    <a href="index.php" class="btn-home"><i class="fas fa-arrow-left"></i></a>
    <h2>🔑 Login</h2>
    
    <?php if (isset($_GET['success'])) echo "<p class='success'>".$_GET['success']."</p>"; ?>
    <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>

    <form method="POST" action="">
      <div class="input-group">
        <i class="fas fa-user"></i>
        <input type="text" name="username" placeholder="Username or Email" required>
      </div>
      <div class="input-group">
        <i class="fas fa-lock"></i>
        <input type="password" name="password" placeholder="Password" required>
      </div>
      <button type="submit" class="btn">Login</button>
    </form>

    <div class="switch">
      <p>Don’t have an account? <a href="register.php">Register here</a></p>
    </div>
  </div>
</body>
</html>
