<?php
include '../includes/auth.php';
include '../config/db.php';
$id = intval($_GET['id'] ?? 0);
if (!$id) { header('Location: dashboard.php'); exit; }

// Fetch crime
$stmt = $conn->prepare('SELECT c.*, u.fname AS reporter, o.name AS officer FROM crimes c LEFT JOIN users u ON c.reporter_id=u.id LEFT JOIN users o ON c.officer_id=o.id WHERE c.id = ? LIMIT 1');
$stmt->bind_param('i', $id);
$stmt->execute();
$crime = $stmt->get_result()->fetch_assoc();
if (!$crime) { header('Location: dashboard.php'); exit; }

// Evidence and updates
$evidence = $conn->query('SELECT * FROM evidence WHERE crime_id = ' . $id);
$updates = $conn->query('SELECT cu.*, u.fname FROM case_updates cu JOIN users u ON cu.user_id=u.id WHERE cu.crime_id = ' . $id . ' ORDER BY cu.created_at DESC');

include '../includes/header.php';
?>
<div class="card shadow-sm mb-3">
  <div class="card-body">
    <h3><?php echo htmlspecialchars($crime['title']); ?></h3>
    <p><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($crime['description'])); ?></p>
    <p><strong>Category:</strong> <?php echo htmlspecialchars($crime['category']); ?> | <strong>Severity:</strong> <?php echo htmlspecialchars($crime['severity']); ?></p>
    <p><strong>Status:</strong> <span class="badge <?php echo ($crime['status']=='Resolved')?'bg-success':'bg-warning'; ?>"><?php echo htmlspecialchars($crime['status']); ?></span></p>
    <p><strong>Reporter:</strong> <?php echo htmlspecialchars($crime['reporter']); ?> | <strong>Officer:</strong> <?php echo htmlspecialchars($crime['officer']) ?: 'Unassigned'; ?></p>
    <p><strong>Address:</strong> <?php echo htmlspecialchars($crime['address']); ?></p>
  </div>
</div>

<h4>Evidence</h4>
<div class="row">
<?php while ($e = $evidence->fetch_assoc()): ?>
  <div class="col-md-3 mb-3">
    <div class="card">
      <div class="card-body text-center">
        <a class="btn btn-sm btn-outline-dark" href="../uploads/<?php echo rawurlencode($e['file_path']); ?>" target="_blank">View File</a>
        <p class="small mt-2"><?php echo htmlspecialchars($e['uploaded_at']); ?></p>
      </div>
    </div>
  </div>
<?php endwhile; ?>
</div>
<a class="btn btn-sm btn-primary mb-3" href="evidezx`nce_upload.php?id=<?php echo $id; ?>">Upload Evidence</a>

<h4>Case Updates</h4>
<ul class="list-group mb-3">
<?php while ($u = $updates->fetch_assoc()): ?>
  <li class="list-group-item">
    <strong><?php echo htmlspecialchars($u['name']); ?>:</strong> [<?php echo htmlspecialchars($u['status']); ?>] - <?php echo htmlspecialchars($u['comment']); ?>
    <span class="text-muted float-end"><?php echo $u['created_at']; ?></span>
  </li>
<?php endwhile; ?>
</ul>

<?php if (in_array($_SESSION['role_id'], [1,2])): ?>
  <a class="btn btn-warning" href="case_update.php?id=<?php echo $id; ?>">Add Update</a>
<?php endif; ?>

<?php if ($_SESSION['role_id'] == 1): ?>
  <a class="btn btn-info" href="assign_case.php?id=<?php echo $id; ?>">Assign Officer</a>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>