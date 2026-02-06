<?php
include 'includes/db.php';

if (!isset($_GET['report_id']) || !is_numeric($_GET['report_id'])) {
    die("Invalid report ID.");
}

$report_id = (int) $_GET['report_id'];
$result = $conn->query("SELECT * FROM reports WHERE report_id='$report_id'");

if (!$result || $result->num_rows === 0) {
    die("Report not found.");
}

$report = $result->fetch_assoc();

// Convert evidence image to Base64 to ensure printing works
$evidencePath = '';
if (!empty($report['evidence'])) {
    $fileName = trim($report['evidence']);
    $fullPath = __DIR__ . '/uploads/' . $fileName;

    if (file_exists($fullPath)) {
        $type = pathinfo($fullPath, PATHINFO_EXTENSION);
        $data = file_get_contents($fullPath);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        $evidencePath = $base64; 
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Report Summary - Crime Tracker</title>
<style>
body {
    font-family: 'Segoe UI', Arial, sans-serif;
    background: #f2f2f2;
    color: #333;
    margin: 0;
    padding: 0;
}
.report-container {
    max-width: 700px;
    margin: 60px auto;
    background: #fff;
    padding: 40px;
    border-radius: 16px;
    box-shadow: 0 6px 16px rgba(0,0,0,0.15);
}
.report-title {
    text-align: center;
    color: #007bff;
    font-size: 26px;
    margin-bottom: 25px;
}
.report-summary p {
    line-height: 1.7;
    margin: 10px 0;
    font-size: 15px;
}
.evidence-img {
    width: 100%;
    max-width: 550px;
    border-radius: 12px;
    margin: 12px auto;
    display: block;
    border: 1px solid #ddd;
}
.btn-container {
    display: flex;
    justify-content: center;
    gap: 20px;
    margin-top: 35px;
}
button, .btn-home {
    background: #007bff;
    color: #fff;
    border: none;
    padding: 10px 20px;
    border-radius: 8px;
    font-size: 15px;
    cursor: pointer;
    text-decoration: none;
    transition: background 0.3s ease;
}
button:hover, .btn-home:hover {
    background: #0056b3;
}

@media print {
    body { background: #fff !important; color: #000; }
    .report-container { box-shadow: none; border: none; margin: 0; padding: 20px; }
    .btn-container { display: none; }
    .report-title { color: #000; font-size: 22px; text-align: center; border-bottom: 2px solid #000; padding-bottom: 8px; margin-bottom: 20px; }
    .evidence-img { max-width: 100% !important; height: auto !important; margin: 15px auto; display: block; border: 1px solid #000; }
}
</style>
</head>
<body>
<div class="report-container">
<h2 class="report-title">📝 Crime Report Summary</h2>
<div class="report-summary">
    <p><strong>Type:</strong> <?= htmlspecialchars($report['type']) ?></p>
    <p><strong>Location:</strong> <?= htmlspecialchars($report['location']) ?></p>
    <p><strong>Date Reported:</strong> <?= htmlspecialchars($report['date_reported']) ?></p>
    <p><strong>Status:</strong> <?= htmlspecialchars($report['status']) ?></p>
    <p><strong>Reporter:</strong> <?= $report['reporter'] ? htmlspecialchars($report['reporter']) : "Anonymous" ?></p>
    <p><strong>Description:</strong> <?= nl2br(htmlspecialchars($report['description'])) ?></p>

<?php if (!empty($report['evidence']) && file_exists(__DIR__.'/uploads/'.$report['evidence'])): ?>
    <p><strong>Evidence:</strong></p>
    <?php
        $imgData = base64_encode(file_get_contents(__DIR__.'/uploads/'.$report['evidence']));
        $ext = pathinfo($report['evidence'], PATHINFO_EXTENSION);
        $imgSrc = 'data:image/'.$ext.';base64,'.$imgData;
    ?>
    <img src="<?= $imgSrc ?>" alt="Evidence Image" class="evidence-img">
<?php endif; ?>


<div class="btn-container">
    <a href="index.php" class="btn-home">🏠 Close / Go Home</a>
    <button onclick="window.print()">🖨️ Print Report</button>
</div>
</div>
</body>
</html>
