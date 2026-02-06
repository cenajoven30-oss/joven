<?php
session_start();    
include '../config/db.php';
$error = '';
$id = intval($_GET['id'] ?? $_POST['crime_id'] ?? 0);
if (!$id) { header('Location: dashboard.php'); exit; }

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['evidence'])) {
    $file = $_FILES['evidence'];
    $uploads = __DIR__ . '/../uploads';
    if (!is_dir($uploads)) mkdir($uploads, 0755, true);

    $fname = time() . '_' . preg_replace('/[^A-Za-z0-9._-]/', '_', $file['name']);
    $target = $uploads . '/' . $fname;

    if (move_uploaded_file($file['tmp_name'], $target)) {
        $type = mime_content_type($target);
        $stmt = $conn->prepare('INSERT INTO evidence (crime_id,uploaded_by,file_path,file_type) VALUES (?,?,?,?)');
        $stmt->bind_param('iiss', $id, $_SESSION['user_id'], $fname, $type);
        if ($stmt->execute()) {
            header('Location: crime_view.php?id=' . $id);
            exit;
        } else {
            $error = 'DB error: ' . $conn->error;
        }
    } else {
        $error = 'Upload failed.';
    }
}
?>
<div class="card shadow-sm">
  <div class="card-body">
    <h3>Upload Evidence</h3>
    <?php if ($error): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>
    <form method="POST" enctype="multipart/form-data">
      <input type="hidden" name="crime_id" value="<?php echo $id; ?>">
      <div class="mb-3"><input type="file" name="evidence" class="form-control" required></div>
      <button class="btn btn-primary">Upload</button>
    </form>
  </div>
</div>