<?php

use Elagiou\VacationPortal\Helpers\SessionFlash;

$errors = SessionFlash::get('errors', []);
$success = SessionFlash::get('success');

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Employee Dashboard - Vacation Portal</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="/css/app.css" rel="stylesheet">
</head>

<body class="bg-light">

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">Vacation Portal</a>
      <div class="d-flex">
        <span class="navbar-text me-3">
          <?= htmlspecialchars($user['first_name']) ?>
        </span>
        <a class="btn btn-outline-light" href="/logout">Logout</a>
      </div>
    </div>
  </nav>

  <div class="container mt-4">

    <!-- Flash Messages -->
    <?php if (!empty($errors)): ?>
      <div class="alert alert-danger">
        <ul class="mb-0">
          <?php foreach ($errors as $err): ?>
            <li><?= htmlspecialchars($err) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php elseif ($success): ?>
      <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <!-- Header / Action Buttons -->
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h3>Your Vacation Requests</h3>
      <a href="/employee/request/create" class="btn btn-success">➕ Create Vacation Request</a>
    </div>

    <!-- Vacation Requests Table -->
    <div class="card">
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-striped table-hover mb-0">
            <thead class="table-light">
              <tr>
                <th>Date Submitted</th>
                <th>Start</th>
                <th>End</th>
                <th>Reason</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($requests)): ?>
                <tr>
                  <td colspan="6" class="text-center py-3">No vacation requests found.</td>
                </tr>
              <?php else: ?>
                <?php foreach ($requests as $r): ?>
                  <tr>
                    <td><?= $r->created_at ?></td>
                    <td><?= $r->start_date ?></td>
                    <td><?= $r->end_date ?></td>
                    <td><?= htmlspecialchars($r->reason) ?></td>
                    <td><?= ucfirst($r->status_name) ?></td>
                    <td>
                      <?php if ($r->status_name === 'pending'): ?>
                        <form action="/employee/request/delete/<?= $r->id ?>" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this request?');">
                          <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                      <?php else: ?>
                        <span class="text-muted">-</span>
                      <?php endif; ?>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div> <!-- /.container -->

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>