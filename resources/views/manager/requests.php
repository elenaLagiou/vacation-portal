<?php

use Elagiou\VacationPortal\Helpers\SessionFlash;

$errors = SessionFlash::get('errors', []);
$success = SessionFlash::get('success');

// $requests = ... // array of VacationRequestDTO
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manager - Vacation Requests</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <div class="container mx-auto mt-10 p-5 bg-white shadow-md rounded-lg">

        <h2 class="text-3xl font-bold text-gray-800 mb-4">Vacation Requests</h2>
        <hr class="mb-6">

        <?php if (!empty($errors)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <ul class="list-disc pl-5">
                    <?php foreach ($errors as $err): ?>
                        <li><?= htmlspecialchars($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php elseif ($success): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

        <div class="mb-6 flex space-x-2">
            <a href="/manager/home"
                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded flex items-center space-x-2">
                🏠 Home
            </a>
            <a href="/manager/requests"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded flex items-center space-x-2">
                🔄 Refresh
            </a>
        </div>

        <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden mt-6">
            <thead class="bg-gray-800 text-white">
                <tr>
                    <th class="py-3 px-4 uppercase font-semibold text-sm">ID</th>
                    <th class="py-3 px-4 uppercase font-semibold text-sm">Employee</th>
                    <th class="py-3 px-4 uppercase font-semibold text-sm">Reason</th>
                    <th class="py-3 px-4 uppercase font-semibold text-sm">Start Date</th>
                    <th class="py-3 px-4 uppercase font-semibold text-sm">End Date</th>
                    <th class="py-3 px-4 uppercase font-semibold text-sm">Status</th>
                    <th class="py-3 px-4 uppercase font-semibold text-sm">Actions</th>
                </tr>
            </thead>
            <tbody class="text-gray-700">
                <?php if (!empty($requests)): ?>
                    <?php foreach ($requests as $request): ?>
                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                            <td class="py-3 px-4"><?= $request->id ?></td>
                            <td class="py-3 px-4"><?= htmlspecialchars($request->first_name . ' ' . $request->last_name) ?></td>
                            <td class="py-3 px-4"><?= htmlspecialchars($request->reason) ?></td>
                            <td class="py-3 px-4"><?= htmlspecialchars($request->start_date) ?></td>
                            <td class="py-3 px-4"><?= htmlspecialchars($request->end_date) ?></td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold 
                                    <?= $request->status_name === 'approved' ? 'bg-green-200 text-green-800' : ($request->status_name === 'rejected' ? 'bg-red-200 text-red-800' :
                                            'bg-gray-200 text-gray-800') ?>">
                                    <?= ucfirst(htmlspecialchars($request->status_name)) ?>
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex space-x-2">
                                    <?php if ($request->status_name === 'pending'): ?>
                                        <button type="button"
                                            class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-3 rounded text-sm action-btn"
                                            data-action="approve"
                                            data-request-id="<?= $request->id ?>">
                                            ✓ Approve
                                        </button>
                                        <button type="button"
                                            class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded text-sm action-btn"
                                            data-action="reject"
                                            data-request-id="<?= $request->id ?>">
                                            ✗ Reject
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center py-4 text-gray-500">
                            No vacation requests found.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- ✅ Toast container -->
    <div id="toast-container" class="fixed bottom-4 right-4 space-y-2 z-50"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const actionButtons = document.querySelectorAll('.action-btn');

            actionButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const requestId = this.dataset.requestId;
                    const action = this.dataset.action;
                    const url = `/manager/request/${action}/${requestId}`;
                    const isApprove = action === 'approve';

                    fetch(url, {
                            method: 'POST'
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(`Failed to ${isApprove ? 'approve' : 'reject'} request.`);
                            }
                            return response.json();
                        })
                        .then(data => {
                            const row = this.closest('tr');
                            const statusCell = row.querySelector('td:nth-child(6) span');
                            const actionsCell = row.querySelector('td:nth-child(7) .flex'); // ✅ fixed selector

                            // Update status visually
                            statusCell.textContent = isApprove ? 'Approved' : 'Rejected';
                            statusCell.className =
                                'px-2 py-1 rounded-full text-xs font-semibold ' +
                                (isApprove ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800');

                            // Remove buttons
                            if (actionsCell) {
                                actionsCell.querySelectorAll('.action-btn').forEach(btn => btn.remove());
                            }

                            showToast(data.message, isApprove ? 'green' : 'red');
                        })
                        .catch(error => {
                            showToast(error.message, 'red');
                        });
                });
            });

            // ✅ Tailwind toast helper
            function showToast(message, color = 'gray') {
                const container = document.getElementById('toast-container');
                const toast = document.createElement('div');
                toast.className = `px-4 py-2 rounded shadow text-white bg-${color}-500`;
                toast.textContent = message;

                container.appendChild(toast);
                setTimeout(() => toast.remove(), 2500);
            }
        });
    </script>

</body>

</html>