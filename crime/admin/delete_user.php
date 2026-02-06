<?php
session_start();
include '../includes/db.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int) $_GET['id'];
    $conn->query("DELETE FROM users WHERE user_id=$id");
}
header("Location: manage_users.php");
exit;
