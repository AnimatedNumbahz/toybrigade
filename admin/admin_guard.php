<?php
/**
 * Strict admin-only access gate.
 * Usage: include this at the VERY TOP of each admin page.
 */
include_once 'connection.php';

/** Not an admin? Send 403 and render a minimal page; do NOT redirect to AdminIndex. */
if (empty($_SESSION['admin_role']) || $_SESSION['admin_role'] !== 'admin') {
    http_response_code(403);
    header('Cache-Control: no-store');
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>403 Forbidden</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex vh-100 align-items-center justify-content-center bg-light">
  <div class="text-center">
    <h1 class="display-5 fw-bold text-danger">403 • Forbidden</h1>
    <p class="lead mb-4">You don’t have permission to access this page.</p>
    <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
      <a href="login.php" class="btn btn-primary btn-lg px-4 gap-3">Go to Login</a>
      <a href="logout.php" class="btn btn-outline-secondary btn-lg px-4">Back to Shop</a>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
    exit();
}