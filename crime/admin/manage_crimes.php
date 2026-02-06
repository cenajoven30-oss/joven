<?php
session_start();
include '../includes/db.php';
// ✅ Handle Quick Status Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['report_id'], $_POST['status'])) {
    $report_id = intval($_POST['report_id']);
    $status = $conn->real_escape_string($_POST['status']);
    $assigned_date = $conn->real_escape_string($_POST['assigned_date'] ?? date('Y-m-d'));

    $update_sql = "UPDATE reports 
                   SET status='$status', assigned_date='$assigned_date' 
                   WHERE report_id=$report_id";
    $conn->query($update_sql);
    // Redirect to avoid resubmission
    header("Location: manage_crimes.php");
    exit;
}


// ✅ Filters
$where = [];
if (!empty($_GET['type'])) {
    $type = $conn->real_escape_string($_GET['type']);
    $where[] = "r.type LIKE '%$type%'";
}
if (!empty($_GET['status'])) {
    $status = $conn->real_escape_string($_GET['status']);
    $where[] = "r.status = '$status'";
}

$sql = "SELECT r.*, u.fname, u.email 
        FROM reports r
        LEFT JOIN users u ON r.user_id = u.user_id";

if ($where) {
    $sql .= " WHERE " . implode(" AND ", $where);
}

$sql .= " ORDER BY r.date_reported DESC";

$result = $conn->query($sql);


?>

<link rel="stylesheet" href="assets/css/manage.css">

<header class="main-header">
  <div class="header-content">  
    <h1>⚖️ Manage Crimes</h1>
    <nav class="main-nav">
      <nav class="main-nav">
      <a href="home.php" class="active">Home</a>
      <a href="dash.php">Dashboard</a>
      <a href="manage_crimes.php">Manage Crimes</a>
      <a href="manage_users.php">Manage Users</a>
      <a href="../logout.php">Logout</a>
  </div>
</header>

<section class="filters">
  <form method="GET" class="filter-form">
    <div class="form-group">
      <label for="type">Crime Type</label>
      <input type="text" name="type" id="type" placeholder="e.g. Theft" value="<?= htmlspecialchars($_GET['type'] ?? '') ?>">
    </div>

    <div class="form-group">
      <label for="status">Status</label>
      <select name="status" id="status">
        <option value="">All</option>
        <option value="pending" <?= ($_GET['status'] ?? '') === 'pending' ? 'selected' : '' ?>>Pending</option>
        <option value="in-progress" <?= ($_GET['status'] ?? '') === 'in-progress' ? 'selected' : '' ?>>In Progress</option>
        <option value="resolved" <?= ($_GET['status'] ?? '') === 'resolved' ? 'selected' : '' ?>>Resolved</option>
      </select>

       <button type="submit" class="btn-filter">Apply Filter</button>
    </div>


   
  </form>
</section>

<section class="crime-list">
<?php if ($result && $result->num_rows > 0): ?>
  <?php while ($report = $result->fetch_assoc()): ?>
    <div class="crime-card">
      <h3>📝 <?= htmlspecialchars($report['type']) ?></h3>
      <p><strong>ID:</strong> <?= $report['report_id'] ?></p>
      <p><strong>Reporter:</strong> <?= htmlspecialchars($report['username'] ?? 'Guest') ?></p>
      <p><strong>Description:</strong> <?= htmlspecialchars($report['description']) ?></p>
      <p><strong>Location:</strong> <?= htmlspecialchars($report['location']) ?></p>
      <p><strong>Date:</strong> <?= htmlspecialchars($report['date_reported']) ?></p>
      <p><strong>Status:</strong> 
        <span class="status-<?= strtolower($report['status']) ?>">
          <?= htmlspecialchars($report['status']) ?>
        </span>
      </p>
      <p><strong>Evidence:</strong> 
        <?= $report['evidence'] ? '<a href="' . htmlspecialchars($report['evidence']) . '" target="_blank">View Evidence</a>' : "None" ?>
      </p>

      <!-- 🔧 Actions -->
    <!-- 🔧 Actions / Quick Status Update -->
<div class="crime-actions">
    <form method="POST" class="quick-update">
        <input type="hidden" name="report_id" value="<?= $report['report_id'] ?>">
        <select name="status" required>
            <option value="pending" <?= $report['status']=='pending'?'selected':'' ?>>Pending</option>
            <option value="in-progress" <?= $report['status']=='in-progress'?'selected':'' ?>>In Progress</option>
            <option value="resolved" <?= $report['status']=='resolved'?'selected':'' ?>>Resolved</option>
        </select>
        <input type="date" name="assigned_date" value="<?= htmlspecialchars(substr($report['assigned_date'] ?? '',0,10)) ?>">
        <button type="submit" class="btn-update">Update</button>
    
    <a href="delete_rreport.php?id=<?= $report['report_id'] ?>" class="btn-delete" onclick="return confirm('Are you sure you want to delete this report?');">Delete</a>
    </form>
</div>

    </div>
  <?php endwhile; ?>
<?php else: ?>
  <p>No reports found.</p>
<?php endif; ?>
</section>
