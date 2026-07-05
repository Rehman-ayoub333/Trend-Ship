<?php
require_once dirname(__DIR__) . '/api/helpers.php';
requireAdmin();
$pdo      = getDB();
$filter   = $_GET['status'] ?? 'all';
$where    = $filter !== 'all' ? "WHERE status = " . $pdo->quote($filter) : '';
$bookings = $pdo->query("SELECT * FROM bookings $where ORDER BY visit_date ASC, visit_time ASC")->fetchAll();
$active = 'visits';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Visit Registry — TRENDSHIP</title>
<link rel="stylesheet" href="admin.css">
</head>
<body class="admin-body">
<?php require __DIR__ . '/includes/sidebar.php'; ?>
<div class="main">
  <div class="top-bar">
    <div>
      <div class="page-eyebrow">Gather · Visits</div>
      <div class="page-title">Visit Registry <span style="color:var(--muted);font-size:18px">(<?= count($bookings) ?>)</span></div>
    </div>
    <div class="filter-pills">
      <a href="?status=all"       class="pill <?= $filter==='all'?'active':'' ?>">All</a>
      <a href="?status=pending"   class="pill <?= $filter==='pending'?'active':'' ?>">Awaiting</a>
      <a href="?status=confirmed" class="pill <?= $filter==='confirmed'?'active':'' ?>">Welcomed</a>
      <a href="?status=cancelled" class="pill <?= $filter==='cancelled'?'active':'' ?>">Released</a>
    </div>
  </div>

  <?php if (empty($bookings)): ?>
    <div class="empty">No visits registered yet.</div>
  <?php else: ?>
  <table>
    <thead><tr><th>Ref</th><th>Name</th><th>Email</th><th>Date</th><th>Time</th><th>Party</th><th>Status</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($bookings as $b): ?>
      <tr id="row-<?= $b['id'] ?>">
        <td style="font-family:monospace;font-size:11px;color:#6a6058"><?= $b['ref'] ?></td>
        <td><?= htmlspecialchars($b['name']) ?></td>
        <td><?= htmlspecialchars($b['email']) ?></td>
        <td><?= date('D d M Y', strtotime($b['visit_date'])) ?></td>
        <td><?= $b['visit_time'] ?></td>
        <td><?= $b['party_size'] ?></td>
        <td>
          <select class="status-sel" onchange="updateStatus(<?= $b['id'] ?>, this.value)">
            <option <?= $b['status']==='pending'?'selected':'' ?>>pending</option>
            <option <?= $b['status']==='confirmed'?'selected':'' ?>>confirmed</option>
            <option <?= $b['status']==='cancelled'?'selected':'' ?>>cancelled</option>
          </select>
        </td>
        <td><button class="btn btn-retire" onclick="deleteBooking(<?= $b['id'] ?>, '<?= $b['ref'] ?>')">Release</button></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  <?php endif; ?>
</div>
<div class="toast" id="toast"></div>
<script>
async function updateStatus(id, status) {
  const res  = await fetch('../api/bookings/update.php', {
    method:'PUT', headers:{'Content-Type':'application/json'},
    body: JSON.stringify({id, status})
  });
  const data = await res.json();
  showToast(data.message || data.error, !data.success);
}
async function deleteBooking(id, ref) {
  if (!confirm(`Release visit ${ref} from the registry?`)) return;
  const res  = await fetch('../api/bookings/delete.php', {
    method:'DELETE', headers:{'Content-Type':'application/json'},
    body: JSON.stringify({id})
  });
  const data = await res.json();
  if (data.success) { document.getElementById('row-'+id)?.remove(); showToast(data.message); }
  else showToast(data.error, true);
}
function showToast(msg, err=false) {
  const t = document.getElementById('toast');
  t.textContent = msg;
  t.style.borderColor = err ? '#c05050' : '#d4857a';
  t.classList.add('show');
  setTimeout(() => t.classList.remove('show'), 3000);
}
</script>
</body>
</html>
