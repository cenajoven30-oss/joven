<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?error=Please login to report a crime");
    exit();
}

$preview = false;
$photoPath = null;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['confirm'])) {
        // Final insert into DB
        $crime_type = $_POST['crime_type'];
        $location   = $_POST['location'];
        $date       = $_POST['date'];
        $details    = $_POST['details'];
        $reporter   = $_POST['reporter'];
        $user_id    = $_SESSION['user_id'];
        $status     = "Pending";

        // Evidence file name stored in hidden input
        $photoName = $_POST['saved_image'] ?? null;

        // Move temp file to permanent location if exists
        if ($photoName && file_exists('uploads/' . $photoName)) {
            $newPath = 'uploads/' . $photoName;
            // already in uploads, do nothing
        }

        $sql = "INSERT INTO reports (user_id, type, location, date_reported, description, evidence, status, reporter) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isssssss", $user_id, $crime_type, $location, $date, $details, $photoName, $status, $reporter);

        if ($stmt->execute()) {
            $last_id = $stmt->insert_id;
            header("Location: print.php?report_id=" . $last_id);
            exit();
        } else {
            $error = "❌ Something went wrong. Please try again.";
        }

    } elseif (isset($_POST['preview'])) {
        // Preview mode
        $preview    = true;
        $crime_type = $_POST['crime_type'];
        $location   = $_POST['location'];
        $date       = $_POST['date'];
        $details    = $_POST['details'];
        $reporter   = $_POST['reporter'];

        // Handle file temporarily
        if (!empty($_FILES['image']['name'])) {
            $fileName = time() . '_' . preg_replace('/[^A-Za-z0-9_.-]/', '_', basename($_FILES['image']['name']));
            $photoPath = 'uploads/' . $fileName;
            move_uploaded_file($_FILES['image']['tmp_name'], $photoPath);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Report a Crime - Crime Tracker</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">
<link rel="stylesheet" href="assets/css/report.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<br><br><br><br>

<header class="main-header">
  <nav class="main-nav">
    <a href="index.php"><i class="fas fa-home"></i> Home</a>
    <a href="submit.php" class="active"><i class="fas fa-file-alt"></i> Report</a>
  </nav>
</header>

<div class="report-container">
<h2>📝 Report a Crime</h2>

<?php if (isset($error)) echo "<p class='message error'>$error</p>"; ?>

<?php if (!$preview) { ?>
<form method="POST" action="" enctype="multipart/form-data">
    <label for="crime_type">Crime Type</label>
    <select name="crime_type" id="crime_type" required>
        <option value="">-- Select Crime Type --</option>
        <option>Theft</option>
        <option>Robbery</option>
        <option>Assault</option>
        <option>Vandalism</option>
        <option>Drug Related</option>
        <option>Other</option>
    </select>

    <label for="location">Barangay</label>
    <select name="location" id="location" required>
        <option value="">-- Select Barangay --</option>
        <option value="Bunakan">Bunakan</option>
        <option value="Kangwayan">Kangwayan</option>
        <option value="Kaongkod">Kaongkod</option>
        <option value="Kodia">Kodia</option>
        <option value="Maalat">Maalat</option>
        <option value="Malbago">Malbago</option>
        <option value="Mancilang">Mancilang</option>
        <option value="Pili">Pili</option>
        <option value="Poblacion">Poblacion</option>
        <option value="San Agustin">San Agustin</option>
        <option value="Tabagak">Tabagak</option>
        <option value="Tarong">Tarong</option>
        <option value="Talangnan">Talangnan</option>
    </select>

    <label for="date">Date & Time of Incident</label>
    <input type="text" name="date" id="date" required placeholder="Select date & time">

    <label for="evidence">Evidence</label>
    <input type="file" name="image">

    <label for="details">Details</label>
    <textarea name="details" id="details" placeholder="Describe the incident..." required></textarea>

    <label for="reporter">Your Name (optional)</label>
    <input type="text" name="reporter" id="reporter" placeholder="Anonymous if left blank">

    <button type="submit" class="btn-submit" name="preview">Preview Report</button>
</form>

<?php } else { ?>
<form method="POST" action="">
    <div class="preview-box">
        <h3>🔍 Preview Your Report</h3>
        <p><strong>Type:</strong> <?= htmlspecialchars($crime_type) ?></p>
        <p><strong>Location:</strong> <?= htmlspecialchars($location) ?></p>
        <p><strong>Date:</strong> <?= htmlspecialchars($date) ?></p>
        <p><strong>Details:</strong> <?= nl2br(htmlspecialchars($details)) ?></p>
        <p><strong>Reporter:</strong> <?= $reporter ? htmlspecialchars($reporter) : "Anonymous" ?></p>

        <?php if ($photoPath): ?>
            <p><strong>Evidence:</strong></p>
            <img src="<?= htmlspecialchars($photoPath) ?>" class="evidence-img">
        <?php endif; ?>

        <!-- Hidden fields -->
        <input type="hidden" name="crime_type" value="<?= htmlspecialchars($crime_type) ?>">
        <input type="hidden" name="location" value="<?= htmlspecialchars($location) ?>">
        <input type="hidden" name="date" value="<?= htmlspecialchars($date) ?>">
        <input type="hidden" name="details" value="<?= htmlspecialchars($details) ?>">
        <input type="hidden" name="reporter" value="<?= htmlspecialchars($reporter) ?>">
        <input type="hidden" name="saved_image" value="<?= htmlspecialchars(basename($photoPath)) ?>">

        <button type="submit" class="btn-submit" name="confirm">✅ Confirm & Submit</button>
        <button type="button" class="btn-submit" style="background:gray;" onclick="history.back()">⬅ Go Back & Edit</button>
    </div>
</form>
<?php } ?>
</div>

<footer class="main-footer">
<p>&copy; <?= date('Y') ?> Crime Tracker. All rights reserved.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
flatpickr("#date", {
    enableTime: true,
    dateFormat: "Y-m-d H:i",
    maxDate: "today",
    time_24hr: true
});
</script>
</body>
</html>
