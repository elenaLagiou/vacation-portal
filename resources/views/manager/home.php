<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manager Dashboard - Vacation Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Vacation Portal</a>
            <div class="d-flex">
                <span class="navbar-text me-3">
                    <?= htmlspecialchars($currentUser['first_name'] . ' ' . $currentUser['last_name']) ?>
                </span>
                <a class="btn btn-outline-light" href="/logout">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">

        <h3>Registered Users</h3>
        <table class="table table-striped mt-3">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Full Name</th>
                    <th>Role</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= $user->id ?></td>
                        <td><?= htmlspecialchars($user->username) ?></td>
                        <td><?= htmlspecialchars($user->email) ?></td>
                        <td><?= htmlspecialchars($user->first_name . ' ' . $user->last_name) ?></td>
                        <td><?= $user->role_id == 2 ? 'Manager' : 'Employee' ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <hr>

        <div class="mt-4">
            <a href="/manager/create-user" class="btn btn-success">➕ Create New User</a>
        </div>
    </div>

</body>

</html>