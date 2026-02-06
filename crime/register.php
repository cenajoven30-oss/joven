<?php
session_start();
include 'includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fname = trim($_POST['fname']);
    $mname = trim($_POST['mname']);
    $lname = trim($_POST['lname']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $rawPassword = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirm_password']);
    $role = "citizen";

    // Server-side validation
    if (strlen($rawPassword) < 8) {
        $error = "❌ Password must be at least 8 characters long.";
    } elseif ($rawPassword !== $confirmPassword) {
        $error = "❌ Passwords do not match.";
    } elseif (!str_ends_with($email, '@gmail.com')) {
        $error = "❌ Email must be a Gmail address (@gmail.com).";
    } else {
        $password = password_hash($rawPassword, PASSWORD_DEFAULT);
    }

    if (!isset($error)) {
        $checkSql = "SELECT * FROM users WHERE fname = ? OR email = ?";
        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->bind_param("ss", $username, $email);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();

        if ($checkResult->num_rows > 0) {
            $error = "❌ Username or Email already exists.";
        } else {
            $sql = "INSERT INTO users (fname, mname, lname, username, email, password, role, phone) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssssss", $fname, $mname, $lname, $username, $email, $password, $role, $phone);

            if ($stmt->execute()) {
                header("Location: login.php?success=✅ Account created successfully! Please log in.");
                exit();
            } else {
                $error = "❌ Something went wrong. Please try again.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Register - Crime Tracker</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="assets/css/register.css">
</head>
<body>

<div class="auth-box">
  <a href="index.php" class="btn-home"><i class="fas fa-arrow-left"></i></a>
  <h2>📝 Register</h2>

  <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>

  <form method="POST" action="">
    <!-- Name group horizontal -->
    <div class="horizontal-group">
      <div class="input-group">
        <i class="fas fa-user"></i>
        <input type="text" name="fname" placeholder="First Name" required>
      </div>
      <div class="input-group">
        <i class="fas fa-user"></i>
        <input type="text" name="mname" placeholder="Middle Name">
      </div>
      <div class="input-group">
        <i class="fas fa-user"></i>
        <input type="text" name="lname" placeholder="Last Name" required>
      </div>
    </div>

    <!-- Other inputs horizontal -->
    <div class="horizontal-group">
      <div class="input-group">
        <i class="fas fa-user-plus"></i>
        <input type="text" name="username" placeholder="Username" required>
      </div>
      <div class="input-group">
        <i class="fas fa-envelope"></i>
        <input type="email" name="email" id="email" placeholder="Email (@gmail.com)" required>
        <small id="emailError" style="color:#f44336; display:none;">Email must be a Gmail address (@gmail.com).</small>
      </div>
      <div class="input-group">
        <i class="fas fa-phone"></i>
        <input type="text" name="phone" placeholder="Phone" required>
      </div>
    </div>

    <!-- Password inputs horizontal -->
    <div class="horizontal-group">
      <div class="input-group">
        <i class="fas fa-lock"></i>
        <input type="password" name="password" id="password" placeholder="Password" required>
        <small id="passwordError" style="color:#f44336; display:none;">Password must be at least 8 characters.</small>
      </div>
      <div class="input-group">
        <i class="fas fa-lock"></i>
        <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password" required>
        <small id="confirmPasswordError" style="color:#f44336; display:none;">Passwords do not match.</small>
      </div>
    </div>

    <button type="submit" class="btn">Register</button>
  </form>

  <div class="switch">
    <p>Already have an account? <a href="login.php">Login here</a></p>
  </div>
</div>

<script>
const passwordInput = document.getElementById('password');
const confirmInput = document.getElementById('confirm_password');
const passwordError = document.getElementById('passwordError');
const confirmError = document.getElementById('confirmPasswordError');
const emailInput = document.getElementById('email');
const emailError = document.getElementById('emailError');

// Password validation
passwordInput.addEventListener('input', () => {
  passwordError.style.display = passwordInput.value.length < 8 ? 'block' : 'none';
});
confirmInput.addEventListener('input', () => {
  confirmError.style.display = confirmInput.value !== passwordInput.value ? 'block' : 'none';
});

// Email validation
emailInput.addEventListener('input', () => {
  emailError.style.display = !emailInput.value.endsWith('@gmail.com') ? 'block' : 'none';
});

// Form submit validation
document.querySelector('form').addEventListener('submit', function(e) {
  if(passwordInput.value.length < 8 || passwordInput.value !== confirmInput.value || !emailInput.value.endsWith('@gmail.com')) {
    e.preventDefault();
    alert("Please fix errors before submitting.");
  }
});
</script>

</body>
</html>
