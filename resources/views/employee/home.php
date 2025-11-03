<h1>Welcome, <?= htmlspecialchars($user['first_name']) ?></h1>
<a href="/employee/request/create">Create Vacation Request</a> |
<a href="/logout">Logout</a>

<h2>Your Vacation Requests</h2>
<table border="1" cellpadding="6">
  <tr>
    <th>Date Submitted</th>
    <th>Start</th>
    <th>End</th>
    <th>Reason</th>
    <th>Status</th>
    <th>Actions</th>
  </tr>
  <?php foreach ($requests as $r): ?>
    <tr>
      <td><?= $r['created_at'] ?></td>
      <td><?= $r['start_date'] ?></td>
      <td><?= $r['end_date'] ?></td>
      <td><?= htmlspecialchars($r['reason']) ?></td>
      <td><?= ucfirst($r['status']) ?></td>
      <td>
        <?php if ($r['status'] === 'pending'): ?>
          <form action="/employee/delete/<?= $r['id'] ?>" method="POST" style="display:inline;">
            <button type="submit">Delete</button>
          </form>
        <?php endif; ?>
      </td>
    </tr>
  <?php endforeach; ?>
</table>