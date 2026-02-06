<?php
session_start();
include 'includes/db.php'; // your db connection

$isLoggedIn = isset($_SESSION['user_id']); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Crime Tracker</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="assets/css/style.css">
<script src="assets/js/main.js"></script>
</head>


<style>

  /* Filter Section Styling */
.report-filter {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 15px;
    margin-bottom: 20px;
    background: #f8f9fa; /* light background to match theme */
    padding: 12px 20px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

.report-filter label {
    font-weight: 600;
    color: #333;
}

.report-filter select {
    padding: 8px 12px;
    border-radius: 8px;
    border: 1px solid #ccc;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.3s ease;
    background: #fff;
}

.report-filter select:hover {
    border-color: #007bff;
    box-shadow: 0 2px 8px rgba(0,123,255,0.2);
}

.report-filter select:focus {
    outline: none;
    border-color: #007bff;
    box-shadow: 0 2px 10px rgba(0,123,255,0.25);
}

</style>
<body>
  
  <!-- Header -->
  <header class="main-header">
    <nav class="main-nav">
      
        <?php if ($isLoggedIn): ?>
          <a href="#home"><i class="fas fa-home"></i> HOME</a>
      <a href="submit.php"><i class="fas fa-file-alt"></i> REPORT</a>
        <a href="#reports"><i class="fas fa-user"></i> MY REPORTS</a>
        
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> LOG OUT</a>
      <?php else: ?>
        <a href="#home"><i class="fas fa-home"></i> HOME</a>
      <a href="submit.php"><i class="fas fa-file-alt"></i> REPORT</a>
      <a href="#features"><i class="fas fa-file-alt"></i> FEATURES</a>
       <a href="#about"><i class="fas fa-info-circle">  ABOUT US</i></a> 
      <?php endif; ?>
   
   
  </nav>
  </header>

  <?php if (!$isLoggedIn): ?>
    <!-- Hero Section (for guests) -->
     <br id="home"> 
    <section class="hero">
      <div class="glow-circle"></div>
      <h2>👮 Welcome to Crime Tracker</h2>
      <div class="typing">Report. Track. Prevent.</div>
      <p>A secure platform to report incidents, analyze crime trends, and keep the Municipality of Madridejos safe.</p>
      <div class="action-links">
        <a href="login.php" class="btn btn-primary">Login</a>
        <a href="register.php" class="btn btn-secondary">Register</a>
      </div>
    </section>
 <!-- Features Section -->
  <br id="features">
    <br>
      <br>
        <br>
          
  <section class="features">
    <h2>✨ Key Features</h2>
    <div class="feature-grid">
      <div class="feature-card">
        <i class="fas fa-lock"></i>
        <h3>Secure Reporting</h3>
        <p>Submit crime reports safely with strong data protection.</p>
      </div>
      <div class="feature-card">
        <i class="fas fa-chart-line"></i>
        <h3>Real-Time Analytics</h3>
        <p>Track trends and analyze data instantly with visual dashboards.</p>
      </div>
      <div class="feature-card">
        <i class="fas fa-users-cog"></i>
        <h3>Role-Based Access</h3>
        <p>Different roles for citizens, barangay staff, and admin officials.</p>
      </div>
      <div class="feature-card">
        <i class="fas fa-print"></i>
        <h3>Printable Summaries</h3>
        <p>Easily generate and print summaries of your crime reports.</p>
      </div>
      <div class="feature-card">
        <i class="fas fa-map-marker-alt"></i>
        <h3>Barangay Insights</h3>
        <p>Get localized reports and crime trends per barangay.</p>
      </div>
    </div>
  </section>
    <!-- About Section -->
     <br>
          <br id="about">
          <br>
          
   <!-- About Section -->
    <section class="about-us">
  <div class="about-container">
    <h2 class="about-title" style="font-size: 50px;">📍 About the Crime Tracking System</h2>
    <p class="about-intro">The system helps residents and authorities in <strong>Madridejos</strong> report and respond to crime efficiently.</p>

    <div class="about-cards">
      <div class="about-card">
        <i class="fas fa-bullseye card-icon"></i>
        <h3>🎯 Purpose</h3>
        <p>Empowering citizens and officials with secure crime reports and data-driven insights.</p>
      </div>

      <div class="about-card">
        <i class="fas fa-lock card-icon"></i>
        <h3>🔒 Features</h3>
        <ul>
          <li>✔ Secure crime reporting</li>
          <li>✔ Real-time analytics</li>
          <li>✔ Role-based access</li>
          <li>✔ Printable summaries</li>
          <li>✔ Barangay-level insights</li>
        </ul>
      </div>

      <div class="about-card">
        <i class="fas fa-globe card-icon"></i>
        <h3>🌍 Local Impact</h3>
        <p>Focused on <strong>Madridejos</strong> for actionable, community-driven safety improvements.</p>
      </div>
    </div>
  </div>
</section>

              
  <?php else: ?>
    <br id="home">
    <section class="hero">
      <div class="glow-circle"></div>
      <h2>👮 Welcome to Crime Tracker</h2>
      <div class="typing">Report. Track. Prevent.</div>
      <p>A secure platform to report incidents, analyze crime trends, and keep your community safe.</p>
    </section>
    <!-- Crime Reports Section (for logged in users) -->
     <br id="reports">
      <br>
        <br>
          <br>
            <br>
          <br>
          
<section  class="reports">
  <h2>📊 Your Crime Reports</h2>
  <div class="report-table-wrapper">
 <div class="report-filter">
  <label for="sortOrder">Sort by:</label>
  <select id="sortOrder" onchange="updateReports()">
    <option value="DESC">Newest to Oldest</option>
    <option value="ASC">Oldest to Newest</option>
  </select>

  <label for="statusFilter">Status:</label>
  <select id="statusFilter" onchange="updateReports()">
    <option value="all">All</option>
    <option value="pending">Pending</option>
    <option value="resolved">Resolved</option>
  </select>
</div>



    <table class="report-table">
      <thead>
        <tr>
          <th>REPORT ID</th>
          <th>Type</th>
          <th>Date</th>
          <th>Location</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
    <tbody id="reportBody">
    <?php
    $userId = $_SESSION['user_id'];
    $sql = "SELECT report_id, type, date_reported, location, status 
            FROM reports 
            WHERE user_id = ? 
            ORDER BY date_reported DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
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
          <?php elseif ($row['status'] == "Approved"): ?>
            <span class="status approved">Approved</span>
          <?php else: ?>  
            <span class="status rejected"><?= htmlspecialchars($row['status']) ?></span>
          <?php endif; ?>
        </td>
       <td>
          <button class="view-btn" onclick="viewReport(<?= $row['report_id'] ?>)">View</button>
       </td>
      </tr>
    <?php endwhile; else: ?>
      <tr><td colspan="6" style="text-align:center;">No reports found.</td></tr>
    <?php endif; ?>
</tbody>

    </table>
  </div>
</section>


  <?php endif; ?>
  <!-- Footer -->
  <footer class="main-footer">
    <p>&copy; <?= date('Y') ?> Crime Tracker. All rights reserved.</p>
  </footer>
  

  <!-- Modal Structure -->
<div id="reportModal" class="modal">
  <div class="modal-content">
    <span class="close-btn" onclick="closeModal()">&times;</span>
    <h2>📝 Crime Report Summary</h2>
    <div id="modal-body">
      <p>Loading...</p>
    </div>
  </div>
</div>
<script>
function updateReports() {
    const order = document.getElementById('sortOrder').value;
    const status = document.getElementById('statusFilter').value;

    fetch(`fetch_reports.php?order=${order}&status=${status}`)
        .then(response => response.text())
        .then(data => {
            document.getElementById('reportBody').innerHTML = data;
        })
        .catch(err => console.error("Error fetching reports:", err));
}

// Load reports on page load
window.addEventListener('DOMContentLoaded', () => {
    updateReports();
});
</script>


</body>


</html>
