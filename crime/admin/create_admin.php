<?php
include '../includes/db.php';

$username = 'admin';
$password = password_hash('admin123', PASSWORD_DEFAULT);

$conn->query("INSERT INTO admins (username, password) VALUES ('$username', '$password')");
echo "✅ Admin created successfully!";
?>
