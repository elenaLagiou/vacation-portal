<h1>Vacation Requests</h1>
<table>
    <tr>
        <th>Employee</th>
        <th>Dates</th>
        <th>Reason</th>
        <th>Status</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($requests as $req): ?>
        <tr>
            <td><?= htmlspecialchars($req['first_name'] . ' ' . $req['last_name']) ?></td>
            <td><?= htmlspecialchars($req['start_date'] . ' to ' . $req['end_date']) ?></td>
            <td><?= htmlspecialchars($req['reason']) ?></td>
            <td><?= htmlspecialchars($req['status']) ?></td>
            <td>
                <?php if ($req['status'] === 'pending'): ?>
                    <form action="/manager/request/approve/<?= $req['id'] ?>" method="POST" style="display:inline;">
                        <button type="submit">Approve</button>
                    </form>
                    <form action="/manager/request/reject/<?= $req['id'] ?>" method="POST" style="display:inline;">
                        <button type="submit">Reject</button>
                    </form>
                <?php else: ?>
                    <?= ucfirst($req['status']) ?>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>