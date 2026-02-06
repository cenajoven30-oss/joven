<?php
session_start();
// ✅ Optional: Ensure only admins can access
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Landing Page</title>
  <link rel="stylesheet" href="assets/css/home.css">
</head>
<body>

<header class="main-header">
  <div class="header-content">  
    <h1>🚔 Crime Tracker Admin</h1>
    <nav class="main-nav">
      <a href="home.php" class="active">Home</a>
      <a href="dash.php">Dashboard</a>
      <a href="manage_crimes.php">Manage Crimes</a>
      <a href="manage_users.php">Manage Users</a>
      <a href="../logout.php">Logout</a>
    </nav>
  </div>
</header>

<section class="hero">
  <div class="hero-content">
    <h2>Welcome Back, Admin 👮</h2>
    <p>Manage crime reports, oversee users, and keep your community safe with ease.</p>
    <div class="hero-buttons">
      <a href="dash.php" class="btn-primary">📊 Go to Dashboard</a>
      <a href="manage_crimes.php" class="btn-secondary">⚖️ Manage Crimes</a>
      <a href="manage_users.php" class="btn-secondary">👥 Manage Users</a>
    </div>
  </div>
</section>

<section class="features">
  <div class="feature-card">
    <div class="feature-icon">📊</div>
    <h3>Dashboard Insights</h3>
    <p>Track reported crimes, statuses, and trends over time with powerful analytics.</p>
  </div>
  <div class="feature-card">
    <div class="feature-icon">⚖️</div>
    <h3>Case Management</h3>
    <p>Update, assign, and resolve crime reports while keeping everything organized.</p>
  </div>
  <div class="feature-card">
    <div class="feature-icon">👥</div>
    <h3>User Control</h3>
    <p>Manage user accounts, assign admin roles, and maintain system security.</p>
  </div>
</section>


<footer class="main-footer">
  <p>&copy; <?= date("Y") ?> Crime Tracker Admin | Powered by Justice</p>
</footer>

</body>
</html>
