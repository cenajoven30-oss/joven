<?php
include 'includes/db.php';

$id = (int) $_GET['id'];
$sql = "SELECT * FROM reports WHERE report_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<p>No report found.</p>";
    exit;
}

$report = $result->fetch_assoc();

// Evidence image path
$evidenceImg = '';
if (!empty($report['evidence'])) {
    $fileName = basename($report['evidence']); // prevent path traversal
    $filePath = 'uploads/' . $fileName; // relative URL

    if (file_exists($filePath)) {
        $evidenceImg = $filePath;
    }
}
?>

<p><strong>Type:</strong> <?= htmlspecialchars($report['type']) ?></p>

<?php if ($evidenceImg): ?>
    <p><strong>Evidence:</strong></p>
    <img src="<?= htmlspecialchars($evidenceImg) ?>" alt="Evidence Image" style="max-width:100%; height:auto; border:1px solid #ccc; margin:10px 0;">
<?php else: ?>
    <p><strong>Evidence:</strong> None provided</p>
<?php endif; ?>

<p><strong>Description:</strong> <?= nl2br(htmlspecialchars($report['description'])) ?></p>
<p><strong>Location:</strong> <?= htmlspecialchars($report['location']) ?></p>
<p><strong>Date Reported:</strong> <?= htmlspecialchars($report['date_reported']) ?></p>
<p><strong>Status:</strong> <?= htmlspecialchars($report['status']) ?></p>
<p><strong>Reporter:</strong> <?= $report['reporter'] ? htmlspecialchars($report['reporter']) : "Anonymous" ?></p>
