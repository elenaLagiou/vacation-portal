<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Create Vacation Request</title>
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
                    <?= htmlspecialchars($user->first_name) ?>
                </span>
                <a class="btn btn-outline-light" href="/logout">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">

        <h3 class="mb-4">Create Vacation Request</h3>

        <div class="card">
            <div class="card-body">
                <form action="/employee/request/create" method="POST">

                    <div class="mb-3">
                        <label for="start_date" class="form-label">Start Date:</label>
                        <input type="date" id="start_date" name="start_date" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="end_date" class="form-label">End Date:</label>
                        <input type="date" id="end_date" name="end_date" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="reason" class="form-label">Reason:</label>
                        <textarea id="reason" name="reason" class="form-control" rows="4" required></textarea>
                    </div>

                    <button type="submit" class="btn btn-success">Submit Request</button>
                    <a href="/employee/home" class="btn btn-secondary ms-2">Back</a>

                </form>
            </div>
        </div>

    </div>

    <!-- Footer -->
    <footer class="footer">
        &copy; <?= date('Y') ?> Vacation Portal. All rights reserved.
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>