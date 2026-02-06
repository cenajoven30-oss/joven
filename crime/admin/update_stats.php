<?php
session_start();
include '/crime/includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    die("Access denied.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reportId = intval($_POST['report_id']);
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE reports SET status = ? WHERE report_id = ?");
    $stmt->bind_param("si", $status, $reportId);

    if ($stmt->execute()) {
        header("Location: dash.php?success=1");
        exit;
    } else {
        echo "Error updating status.";
    }
}
