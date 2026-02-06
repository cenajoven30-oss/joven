<?php
session_start();
include '../includes/db.php';

// ✅ Counts for stats
$totalReports = $conn->query("SELECT COUNT(*) as count FROM reports")->fetch_assoc()['count'];
$pendingReports = $conn->query("SELECT COUNT(*) as count FROM reports WHERE status='pending'")->fetch_assoc()['count'];
$inProgressReports = $conn->query("SELECT COUNT(*) as count FROM reports WHERE status='in-progress'")->fetch_assoc()['count'];
$resolvedReports = $conn->query("SELECT COUNT(*) as count FROM reports WHERE status='resolved'")->fetch_assoc()['count'];

// ✅ Latest Reports
$latestReports = $conn->query("
    SELECT r.*, u.fname AS reporter_name 
    FROM reports r 
    LEFT JOIN users u ON r.user_id = u.user_id 
    ORDER BY r.date_reported DESC 
    LIMIT 5
");


// ✅ Reports over time (monthly)
$reportsOverTime = $conn->query("
    SELECT DATE_FORMAT(date_reported, '%Y-%m') as month, COUNT(*) as total 
    FROM reports 
    GROUP BY month 
    ORDER BY month ASC
");
$months = [];
$reportCounts = [];
while ($row = $reportsOverTime->fetch_assoc()) {
    $months[] = $row['month'];
    $reportCounts[] = $row['total'];
}

// ✅ Reports by type
$reportsByType = $conn->query("
    SELECT type, COUNT(*) as total 
    FROM reports 
    GROUP BY type 
    ORDER BY total DESC 
    LIMIT 5
");
$types = [];
$typeCounts = [];
while ($row = $reportsByType->fetch_assoc()) {
    $types[] = $row['type'];
    $typeCounts[] = $row['total'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>📊 Dashboard - Crime Tracker</title>
  <link rel="stylesheet" href="assets/css/dash.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
  <header class="main-header">
    <div class="header-content">  
      <h1>🕵️‍♂️ Crime Tracking System - Dashboard</h1>
      <nav class="main-nav">
       <a href="home.php" class="active">Home</a>
      <a href="dash.php">Dashboard</a>
      <a href="manage_crimes.php">Manage Crimes</a>
      <a href="manage_users.php">Manage Users</a>
      <a href="../logout.php">Logout</a>
      </nav>
    </div>
  </header>

  <main class="dashboard">
    <!-- ✅ Stats Cards -->
    <section class="stats">
      <div class="card total">
        <h2><?= $totalReports ?></h2>
        <p>Total Reports</p>
      </div>
      <div class="card pending">
        <h2><?= $pendingReports ?></h2>
        <p>Pending</p>
      </div>
      <div class="card in-progress">
        <h2><?= $inProgressReports ?></h2>
        <p>In Progress</p>
      </div>
      <div class="card resolved">
        <h2><?= $resolvedReports ?></h2>
        <p>Resolved</p>
      </div>
    </section>

    <!-- ✅ Charts Section -->
    <section class="charts">
      <div class="chart-card">
        <h3>Reports by Status</h3>
        <canvas id="statusChart"></canvas>
      </div>
      <div class="chart-card">
        <h3>Reports Over Time</h3>
        <canvas id="timeChart"></canvas>
      </div>
      <div class="chart-card">
        <h3>Top Crime Types</h3>
        <canvas id="typeChart"></canvas>
      </div>
    </section>

    <!-- ✅ Recent Reports -->
    <section class="recent-reports">
      <h2>📌 Latest Reports</h2>
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Type</th>
            <th>Reporter</th>
            <th>Status</th>
            <th>Date</th>
          </tr>
        </thead>
       <tbody>
  <?php while ($row = $latestReports->fetch_assoc()): ?>
    <tr>
      <td>#<?= $row['report_id'] ?></td>
      <td><?= htmlspecialchars($row['type']) ?></td>
      <td><?= $row['reporter_name'] ? htmlspecialchars($row['reporter_name']) : "Anonymous" ?></td>
      <td>
        <span class="status-<?= strtolower($row['status']) ?>">
          <?= htmlspecialchars($row['status']) ?>
        </span>
      </td>
      <td><?= htmlspecialchars($row['date_reported']) ?></td>
    </tr>
  <?php endwhile; ?>
</tbody>

      </table>
    </section>
  </main>

<script>
const statusChart = new Chart(document.getElementById('statusChart'), {
  type: 'pie',
  data: {
    labels: ['Pending', 'In Progress', 'Resolved'],
    datasets: [{
      data: [<?= $pendingReports ?>, <?= $inProgressReports ?>, <?= $resolvedReports ?>],
      backgroundColor: ['#facc15', '#f97316', '#22c55e']
    }]
  }
});

const timeChart = new Chart(document.getElementById('timeChart'), {
  type: 'line',
  data: {
    labels: <?= json_encode($months) ?>,
    datasets: [{
      label: 'Reports per Month',
      data: <?= json_encode($reportCounts) ?>,
      borderColor: '#3b82f6',
      backgroundColor: 'rgba(59,130,246,0.2)',
      fill: true
    }]
  }
});

const typeChart = new Chart(document.getElementById('typeChart'), {
  type: 'bar',
  data: {
    labels: <?= json_encode($types) ?>,
    datasets: [{
      label: 'Number of Reports',
      data: <?= json_encode($typeCounts) ?>,
      backgroundColor: '#38bdf8'
    }]
  }
});
</script>
</body>
</html>
