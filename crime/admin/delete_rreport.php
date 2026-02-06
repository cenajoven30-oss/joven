<?php
session_start();
include '../includes/db.php';


// Check if report ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("❌ Invalid report ID.");
}

$report_id = (int) $_GET['id'];

// First, get the evidence file path
$result = $conn->query("SELECT evidence FROM reports WHERE report_id=$report_id");
if ($result && $result->num_rows > 0) {
    $report = $result->fetch_assoc();
    if (!empty($report['evidence']) && file_exists('../' . $report['evidence'])) {
        unlink('../' . $report['evidence']); // Delete the evidence file
    }

    // Delete report from database
    $conn->query("DELETE FROM reports WHERE report_id=$report_id");
}

// Redirect back to manage page
header("Location: manage_crimes.php");
exit();
?>
