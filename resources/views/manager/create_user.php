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

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
        <?php elseif (isset($_GET['success'])): ?>
            <div class="alert alert-success">User created successfully!</div>
        <?php endif; ?>

        <form method="POST" action="/manager/create-user" class="row g-3 mt-2">
            <div class="col-md-3">
                <label for="role_id" class="form-label">Role</label>
                <select name="role_id" id="role_id" class="form-select" required>
                    <option value="1">Employee</option>
                    <option value="2">Manager</option>
                </select>
            </div>

            <div class="col-md-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" name="username" class="form-control" id="username" required>
            </div>

            <div class="col-md-3" id="employee_code_field">
                <label for="employee_code" class="form-label">Employee Code</label>
                <input type="text" name="details[employee_code]" class="form-control" id="employee_code">
            </div>

            <div class="col-md-3">
                <label for="first_name" class="form-label">First Name</label>
                <input type="text" name="first_name" class="form-control" id="first_name" required>
            </div>

            <div class="col-md-3">
                <label for="last_name" class="form-label">Last Name</label>
                <input type="text" name="last_name" class="form-control" id="last_name" required>
            </div>

            <div class="col-md-4">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" class="form-control" id="email" required>
            </div>

            <div class="col-md-4">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" class="form-control" id="password" required>
            </div>

            <div class="col-12 mt-3">
                <button type="submit" class="btn btn-primary">Create User</button>
                <a href="/manager/home" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>

    <script>
        const roleSelect = document.getElementById('role_id');
        const employeeCodeField = document.getElementById('employee_code_field');

        function toggleEmployeeCode() {
            if (roleSelect.value == '1') { // Employee
                employeeCodeField.style.display = 'block';
                document.getElementById('employee_code').required = true;
            } else {
                employeeCodeField.style.display = 'none';
                document.getElementById('employee_code').required = false;
            }
        }

        // Initialize on load
        toggleEmployeeCode();

        // Listen for changes
        roleSelect.addEventListener('change', toggleEmployeeCode);
    </script>
</body>

</html>