<?php

use Elagiou\VacationPortal\Helpers\SessionFlash;

// Flash messages
$errors = SessionFlash::get('errors', []);
$success = SessionFlash::get('success');

// $requests: array of VacationRequestDTO
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manager - Vacation Requests</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container mt-5">

        <h2>Vacation Requests</h2>
        <hr>

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

        <div class="mb-3 d-flex gap-2">
            <a href="/manager/home" class="btn btn-secondary">🏠 Home</a>
            <a href="/manager/requests" class="btn btn-primary">🔄 Refresh</a>
        </div>

        <table class="table table-striped mt-3 align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Employee</th>
                    <th>Reason</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($requests)): ?>
                    <?php foreach ($requests as $request): ?>
                        <tr>
                            <td><?= $request->id ?></td>
                            <td><?= htmlspecialchars($request->first_name . ' ' . $request->last_name) ?></td>
                            <td><?= htmlspecialchars($request->reason) ?></td>
                            <td><?= htmlspecialchars($request->start_date) ?></td>
                            <td><?= htmlspecialchars($request->end_date) ?></td>
                            <td><?= htmlspecialchars($request->status_name) ?></td>
                            <td>
                                <div class="d-flex gap-2">
                                    <form method="POST" action="/manager/request/approve/<?= $request->id ?>" class="d-inline">
                                        <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                    </form>

                                    <form method="POST" action="/manager/request/reject/<?= $request->id ?>" class="d-inline">
                                        <button type="submit" class="btn btn-sm btn-warning">Reject</button>
                                    </form>

                                    <form method="POST" action="/manager/request/delete/<?= $request->id ?>" class="d-inline"
                                        onsubmit="return confirm('Are you sure you want to delete this request?')">
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted">No vacation requests found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const actionForms = document.querySelectorAll('form[action*="/request/approve/"], form[action*="/request/reject/"]');

            actionForms.forEach(form => {
                form.addEventListener('submit', function (event) {
                    event.preventDefault();

                    const action = this.action;
                    const isApprove = action.includes('approve');

                    fetch(action, {
                        method: 'POST'
                    })
                    .then(response => {
                        if (response.ok) {
                            return response.json();
                        }
                        throw new Error(`Failed to ${isApprove ? 'approve' : 'reject'} request.`);
                    })
                    .then(data => {
                        alert(data.message);
                        window.location.reload();
                    })
                    .catch(error => {
                        alert(error.message);
                    });
                });
            });
        });
    </script>
</body>

</html>