<?php
require_once dirname(__DIR__) . '/api/helpers.php';
requireAdmin();
$pdo  = getDB();
$subs = $pdo->query(
  "SELECT *, DATE_FORMAT(subscribed_at,'%d %b %Y') as joined FROM subscribers ORDER BY subscribed_at DESC"
)->fetchAll();
$total  = $pdo->query("SELECT COUNT(*) FROM subscribers WHERE status='active'")->fetchColumn();
$active = 'circle';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Inner Circle — TRENDSHIP</title>
<link rel="stylesheet" href="admin.css">
</head>
<body class="admin-body">
<?php require __DIR__ . '/includes/sidebar.php'; ?>
<div class="main">
  <div class="top-bar">
    <div>
      <div class="page-eyebrow">Gather · Circle</div>
      <div class="page-title">Inner Circle</div>
    </div>
    <a href="?export=csv" class="export-btn">Export Registry</a>
  </div>

  <?php
  // Simple CSV export
  if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="trendship_subscribers_' . date('Y-m-d') . '.csv"');
    $out = fopen('php://output', 'w');
    fputcsv($out, ['Email','Name','Source','Status','Joined']);
    foreach ($subs as $s) {
      fputcsv($out, [$s['email'],$s['name'],$s['source'],$s['status'],$s['joined']]);
    }
    fclose($out);
    exit();
  }
  ?>

  <div class="stat-bar">
    <div class="sb"><div class="sb-n"><?= $total ?></div><div class="sb-l">Within the Circle</div></div>
    <div class="sb"><div class="sb-n"><?= count($subs) ?></div><div class="sb-l">Total Registry</div></div>
  </div>

  <table>
    <thead><tr><th>Email</th><th>Name</th><th>Source</th><th>Status</th><th>Joined</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($subs as $s): ?>
      <tr id="sub-<?= $s['id'] ?>">
        <td><?= htmlspecialchars($s['email']) ?></td>
        <td><?= htmlspecialchars($s['name'] ?: '—') ?></td>
        <td><span class="source-pill source-<?= $s['source'] ?>"><?= str_replace('_',' ',$s['source']) ?></span></td>
        <td><span class="status-<?= $s['status'] ?>"><?= $s['status'] ?></span></td>
        <td><?= $s['joined'] ?></td>
        <td>
          <?php if ($s['status']==='active'): ?>
          <button class="btn btn-retire" onclick="unsub('<?= htmlspecialchars($s['email']) ?>', <?= $s['id'] ?>)">Release</button>
          <?php endif; ?>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>
<div class="toast" id="toast"></div>
<script>
async function unsub(email, id) {
  if (!confirm('Release ' + email + ' from the Inner Circle?')) return;
  const res  = await fetch('../api/subscribers/update.php', {
    method:'PUT', headers:{'Content-Type':'application/json'},
    body: JSON.stringify({email, status:'unsubscribed'})
  });
  const data = await res.json();
  if (data.success) {
    const td = document.querySelector('#sub-'+id+' .status-active');
    if (td) { td.className='status-unsubscribed'; td.textContent='unsubscribed'; }
    const btn = document.querySelector('#sub-'+id+' .btn-retire');
    if (btn) btn.remove();
    showToast('Released from the circle');
  } else showToast(data.error, true);
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
