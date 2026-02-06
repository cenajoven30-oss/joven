<?php
session_start();
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM admins WHERE username = ? LIMIT 1");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $admin = $result->fetch_assoc();
        $stored = $admin['password'] ?? '';

        // If stored value is hashed, verify normally
        if (password_verify($password, $stored)) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['username'] = $admin['username'];
            header("Location: home.php");
            exit();
        }

        // Fallback for legacy: if stored is plain-text and matches, rehash and update
        if ($password === $stored) {
            $newHash = password_hash($password, PASSWORD_DEFAULT);
            $u = $conn->prepare("UPDATE admins SET password = ? WHERE id = ?");
            $u->bind_param("si", $newHash, $admin['id']);
            $u->execute();
            $u->close();

            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['username'] = $admin['username'];
            header("Location: home.php");
            exit();
        }

        $_SESSION['login_error'] = "❌ Incorrect password.";
    } else {
        $_SESSION['login_error'] = "❌ Username not found.";
    }

    header("Location: admin_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Login | TrendClothing</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <style>
    body {
      background-color: #fff;
      font-family: "Poppins", sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }

    .admin-login-box {
      background: #111;
      color: #fff;
      padding: 40px;
      border-radius: 12px;
      width: 350px;
      box-shadow: 0 8px 25px rgba(0,0,0,0.3);
    }

    .admin-title {
      text-align: center;
      margin-bottom: 20px;
    }

    label {
      display: block;
      margin-bottom: 8px;
      font-size: 14px;
    }

    input[type="text"], input[type="password"] {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border-radius: 8px;
      border: none;
    }

    button {
      width: 100%;
      background-color: #c2a77f;
      border: none;
      color: #111;
      padding: 10px;
      font-size: 16px;
      border-radius: 8px;
      cursor: pointer;
      transition: 0.3s;
    }

    button:hover {
      background-color: #e0c79e;
    }

    .error {
      background-color: #ffdddd;
      color: #b30000;
      text-align: center;
      padding: 8px;
      margin-bottom: 15px;
      border-radius: 8px;
    }
  </style>
</head>
<body>
  <div class="admin-login-box">
    <h2 class="admin-title">🔐 Admin Login</h2>

    <?php if (isset($_SESSION['login_error'])): ?>
      <div class="error"><?= $_SESSION['login_error']; unset($_SESSION['login_error']); ?></div>
    <?php endif; ?>

    <form method="POST" class="admin-login-form">
      <label for="username">Username</label>
      <input type="text" name="username" id="username" required placeholder="Enter username">

      <label for="password">Password</label>
      <input type="password" name="password" id="password" required placeholder="Enter password">

      <button type="submit" name="login">Login</button>
    </form>
  </div>
</body>
</html>
