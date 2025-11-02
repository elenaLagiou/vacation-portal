<?php

use Elagiou\VacationPortal\Helpers\SessionFlash;

$errors = SessionFlash::get('errors', []);
$success = SessionFlash::get('success');

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

        <table class="table table-striped mt-3">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Employee</th>
                    <th>Reason</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Status</th>
                    <th>Action</th>
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
                            <td>
                                <form method="POST" action="/manager/update-request" class="d-inline">
                                    <input type="hidden" name="id" value="<?= $request->id ?>">
                                    <select name="status_id" class="form-select form-select-sm" onchange="this.form.submit()">
                                        <?php foreach ($statuses as $status): ?>
                                            <option value="<?= $status->id ?>" <?= $request->status_id === $status->id ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($status->status_name) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </form>
                            </td>
                            <td>
                                <form method="POST" action="/manager/delete-request" class="d-inline">
                                    <input type="hidden" name="id" value="<?= $request->id ?>">
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">No requests found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>

</html>