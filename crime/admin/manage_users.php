<?php
session_start();
include '../includes/db.php';

$sql = "SELECT * FROM users ORDER BY user_id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Users</title>
  <link rel="stylesheet" href="assets/css/users.css">
</head>
<body>

<header class="main-header">
  <div class="header-content">  
    <h1>👥 Manage Users</h1>
    <nav class="main-nav">
      <nav class="main-nav">
      <a href="home.php" class="active">Home</a>
      <a href="dash.php">Dashboard</a>
      <a href="manage_crimes.php">Manage Crimes</a>
      <a href="manage_users.php">Manage Users</a>
      <a href="../../logout.php">Logout</a>
    </nav>
  </div>
</header>

<section class="manage-section">
  <h2>Registered Users</h2>
  <?php if ($result && $result->num_rows > 0): ?>
    <table class="styled-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>First Name</th>
           <th>Middle Name</th>
            <th>Last Name</th>
          <th>Email</th>
          <th>Role</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($user = $result->fetch_assoc()): ?>
          <tr>
            <td><?= $user['user_id'] ?></td>
            <td><?= htmlspecialchars($user['fname']) ?></td>
            <td><?= htmlspecialchars($user['mname']) ?></td>
            <td><?= htmlspecialchars($user['lname']) ?></td>
            <td><?= htmlspecialchars($user['email']) ?></td>
            <td><?= $user['role'] == 1 ? "Admin" : "User" ?></td>
            <td>
              <a href="delete_user.php?id=<?= $user['user_id'] ?>" class="btn-delete" onclick="return confirm('Delete this user?');">Delete</a>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p>No users found.</p>
  <?php endif; ?>
</section>

</body>
</html>
