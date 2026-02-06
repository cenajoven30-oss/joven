<?php
session_start();
include '../includes/db.php';

// ✅ Ensure it's a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $report_id = intval($_POST['report_id']);
    $status = $conn->real_escape_string($_POST['status']);

    // ✅ Update status
    $sql = "UPDATE reports SET status = '$status' WHERE report_id = $report_id";
    if ($conn->query($sql)) {
        $_SESSION['message'] = "Report updated successfully.";
    } else {
        $_SESSION['message'] = "Error updating report: " . $conn->error;
    }
}

// ✅ Redirect back
header("Location: manage_crimes.php");
exit;
