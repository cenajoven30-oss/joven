<?php
session_start();
include 'includes/db.php';

$userId = $_SESSION['user_id'] ?? 0;

$order = $_GET['order'] ?? 'DESC';
$order = strtoupper($order) === 'ASC' ? 'ASC' : 'DESC';

$status = $_GET['status'] ?? 'all';

// Build SQL
$sql = "SELECT report_id, type, date_reported, location, status 
        FROM reports 
        WHERE user_id = ?";

$params = [$userId];
$types = "i";

if ($status !== 'all') {
    $sql .= " AND status = ?";
    $params[] = ucfirst($status); // Make first letter uppercase to match DB
    $types .= "s";
}

$sql .= " ORDER BY date_reported $order";

$stmt = $conn->prepare($sql);

if (count($params) === 2) {
    $stmt->bind_param($types, $params[0], $params[1]);
} else {
    $stmt->bind_param($types, $params[0]);
}

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0):
    while ($row = $result->fetch_assoc()):
?>
<tr>
    <td><?= htmlspecialchars($row['report_id']) ?></td>
    <td><?= htmlspecialchars($row['type']) ?></td>
    <td><?= htmlspecialchars($row['date_reported']) ?></td>
    <td><?= htmlspecialchars($row['location']) ?></td>
    <td>
        <?php if ($row['status'] == "Pending"): ?>
            <span class="status pending">Pending</span>
        <?php elseif ($row['status'] == "Resolved"): ?>
            <span class="status approved">Resolved</span>
        <?php else: ?>
            <span class="status rejected"><?= htmlspecialchars($row['status']) ?></span>
        <?php endif; ?>
    </td>
    <td><button class="view-btn" onclick="viewReport(<?= $row['report_id'] ?>)">View</button></td>
</tr>
<?php
    endwhile;
else:
    echo '<tr><td colspan="6" style="text-align:center;">No reports found.</td></tr>';
endif;
