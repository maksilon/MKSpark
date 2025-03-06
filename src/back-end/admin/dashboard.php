<?php
declare(strict_types=1);
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/../config.php';
$config = require __DIR__ . '/../config.php';
$dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4', $config['db']['host'], $config['db']['dbname']);
$db = new PDO($dsn, $config['db']['user'], $config['db']['pass']);
$stmt = $db->query('SELECT full_name, email, reference_number, registration_date, status FROM registrants ORDER BY registration_date DESC');
$registrants = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>MKSpark Admin Dashboard</title>
  <link rel="stylesheet" href="/path/to/local/bootstrap/css/bootstrap.min.css">
</head>
<body>
  <?php include 'includes/header.php'; ?>
  <div class="container mt-3">
    <h2>Registrants</h2>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Full Name</th>
          <th>Email</th>
          <th>Reference Number</th>
          <th>Registration Date</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($registrants as $reg): ?>
          <tr>
            <td><?= htmlspecialchars($reg['full_name']) ?></td>
            <td><?= htmlspecialchars($reg['email']) ?></td>
            <td><?= htmlspecialchars($reg['reference_number']) ?></td>
            <td><?= htmlspecialchars($reg['registration_date']) ?></td>
            <td><?= htmlspecialchars($reg['status']) ?></td>
            <td>
              <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#previewModal">Preview</button>
              <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal">Edit</button>
              <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#confirmModal">Confirm</button>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <script src="/path/to/local/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
