<?php

use Elagiou\VacationPortal\Helpers\SessionFlash;

// Get flash messages
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
</head>

<body class="bg-light">
    <div class="container mt-5">
        <h2>Create New User</h2>
        <hr>

        <!-- Flash Messages -->
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($errors as $err): ?>
                        <li><?= $err ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php endif; ?>

        <form method="POST" action="/manager/create-user" class="row g-3 mt-2">
            <!-- Role -->
            <div class="col-md-3">
                <label for="role_id" class="form-label">Role</label>
                <select name="role_id" id="role_id" class="form-select" required>
                    <option value="1" <?= old('role_id') == '1' ? 'selected' : '' ?>>Employee</option>
                    <option value="2" <?= old('role_id') == '2' ? 'selected' : '' ?>>Manager</option>
                </select>
            </div>

            <!-- Username -->
            <div class="col-md-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" name="username" class="form-control" id="username" value="<?= old('username') ?>" required>
            </div>

            <!-- Employee Code -->
            <div class="col-md-3" id="employee_code_field">
                <label for="employee_code" class="form-label">Employee Code</label>
                <input type="text" name="details[employee_code]" class="form-control" id="employee_code" value="<?= old('details.employee_code', '') ?>">
            </div>

            <!-- First Name -->
            <div class="col-md-3">
                <label for="first_name" class="form-label">First Name</label>
                <input type="text" name="first_name" class="form-control" id="first_name" value="<?= old('first_name') ?>" required>
            </div>

            <!-- Last Name -->
            <div class="col-md-3">
                <label for="last_name" class="form-label">Last Name</label>
                <input type="text" name="last_name" class="form-control" id="last_name" value="<?= old('last_name') ?>" required>
            </div>

            <!-- Email -->
            <div class="col-md-4">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" class="form-control" id="email" value="<?= old('email') ?>" required>
            </div>

            <!-- Password -->
            <div class="col-md-4">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" class="form-control" id="password" required>
            </div>

            <!-- Buttons -->
            <div class="col-12 mt-3">
                <button type="submit" class="btn btn-primary">Create User</button>
                <a href="/manager/home" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>

    <script>
        const roleSelect = document.getElementById('role_id');
        const employeeCodeField = document.getElementById('employee_code_field');
        const employeeCodeInput = document.getElementById('employee_code');

        function toggleEmployeeCode() {
            if (roleSelect.value == '1') { // Employee
                employeeCodeField.style.display = 'block';
                employeeCodeInput.required = true;
            } else { // Manager
                employeeCodeField.style.display = 'none';
                employeeCodeInput.required = false;
            }
        }

        // Initialize on load
        toggleEmployeeCode();

        // Listen for changes
        roleSelect.addEventListener('change', toggleEmployeeCode);
    </script>
</body>

</html>