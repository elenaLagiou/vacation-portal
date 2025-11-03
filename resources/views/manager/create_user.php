<?php

use Elagiou\VacationPortal\Helpers\SessionFlash;

// Flash messages
$errors = SessionFlash::get('errors', []);
$success = SessionFlash::get('success');
$old = SessionFlash::get('old', []);

function old($key, $default = '')
{
    global $old;
    return htmlspecialchars($old[$key] ?? $default);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Create User - Manager Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/app.css" rel="stylesheet">
</head>

<body class="bg-light">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="/manager/home">Vacation Portal</a>
            <div class="d-flex">
                <!-- <span class="navbar-text me-3">
                    <?= htmlspecialchars($user->first_name . ' ' . $user->last_name) ?>
                </span> -->
                <a class="btn btn-outline-light" href="/logout">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">

        <!-- Flash Messages -->
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger shadow-sm rounded">
                <ul class="mb-0">
                    <?php foreach ($errors as $err): ?>
                        <li><?= htmlspecialchars($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php elseif ($success): ?>
            <div class="alert alert-success shadow-sm rounded"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <!-- Card -->
        <div class="card shadow-lg border-0 rounded-4">
            <div class="card-header bg-white border-bottom py-3">
                <h4 class="mb-0 text-primary fw-semibold">Create New User</h4>
            </div>

            <div class="card-body bg-white p-4">
                <form method="POST" action="/manager/create-user" class="row g-3">

                    <!-- Role -->
                    <div class="col-md-3">
                        <label for="role_id" class="form-label fw-semibold">Role</label>
                        <select name="role_id" id="role_id" class="form-select" required>
                            <option value="1" <?= old('role_id') == '1' ? 'selected' : '' ?>>Employee</option>
                            <option value="2" <?= old('role_id') == '2' ? 'selected' : '' ?>>Manager</option>
                        </select>
                    </div>

                    <!-- Username -->
                    <div class="col-md-3">
                        <label for="username" class="form-label fw-semibold">Username</label>
                        <input type="text" name="username" class="form-control" id="username"
                            value="<?= old('username') ?>" required>
                    </div>

                    <!-- Employee Code -->
                    <div class="col-md-3" id="employee_code_field">
                        <label for="employee_code" class="form-label fw-semibold">Employee Code</label>
                        <input type="text" name="details[employee_code]" class="form-control" id="employee_code"
                            value="<?= old('details.employee_code', '') ?>">
                    </div>

                    <!-- First Name -->
                    <div class="col-md-3">
                        <label for="first_name" class="form-label fw-semibold">First Name</label>
                        <input type="text" name="first_name" class="form-control" id="first_name"
                            value="<?= old('first_name') ?>" required>
                    </div>

                    <!-- Last Name -->
                    <div class="col-md-3">
                        <label for="last_name" class="form-label fw-semibold">Last Name</label>
                        <input type="text" name="last_name" class="form-control" id="last_name"
                            value="<?= old('last_name') ?>" required>
                    </div>

                    <!-- Email -->
                    <div class="col-md-4">
                        <label for="email" class="form-label fw-semibold">Email</label>
                        <input type="email" name="email" class="form-control" id="email"
                            value="<?= old('email') ?>" required>
                    </div>

                    <!-- Password -->
                    <div class="col-md-4">
                        <label for="password" class="form-label fw-semibold">Password</label>
                        <input type="password" name="password" class="form-control" id="password" required>
                    </div>

                    <!-- Buttons -->
                    <div class="col-12 mt-4 d-flex justify-content-end gap-2">
                        <a href="/manager/home" class="btn btn-secondary px-4">Cancel</a>
                        <button type="submit" class="btn btn-primary px-4">➕ Create User</button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <footer class="bg-primary text-white text-center py-3 mt-5">
        <small>&copy; <?= date('Y') ?> Vacation Portal. All rights reserved.</small>
    </footer>

    <script>
        const roleSelect = document.getElementById('role_id');
        const employeeCodeField = document.getElementById('employee_code_field');
        const employeeCodeInput = document.getElementById('employee_code');

        function toggleEmployeeCode() {
            if (roleSelect.value === '1') { // Employee
                employeeCodeField.style.display = 'block';
                employeeCodeInput.required = true;
            } else {
                employeeCodeField.style.display = 'none';
                employeeCodeInput.required = false;
            }
        }

        toggleEmployeeCode();
        roleSelect.addEventListener('change', toggleEmployeeCode);
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>