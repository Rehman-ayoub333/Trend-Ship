<?php
require_once dirname(__DIR__) . '/api/helpers.php';
requireAdmin();
$pdo       = getDB();
$downloads = $pdo->query(
  "SELECT *, DATE_FORMAT(downloaded_at,'%d %b %Y %H:%i') as dl_time
   FROM report_downloads ORDER BY downloaded_at DESC"
)->fetchAll();
$unique = $pdo->query("SELECT COUNT(DISTINCT email) FROM report_downloads")->fetchColumn();
$active = 'ledger';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Report Ledger — TRENDSHIP</title>
<link rel="stylesheet" href="admin.css">
</head>
<body class="admin-body">
<?php require __DIR__ . '/includes/sidebar.php'; ?>
<div class="main">
  <div class="top-bar">
    <div>
      <div class="page-eyebrow">Gather · Reports</div>
      <div class="page-title">Report Ledger</div>
    </div>
    <a href="?export=csv" class="export-btn">Export Ledger</a>
  </div>

  <?php
  if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="trendship_downloads_' . date('Y-m-d') . '.csv"');
    $out = fopen('php://output', 'w');
    fputcsv($out, ['Email','Name','Company','Downloaded At']);
    foreach ($downloads as $d) fputcsv($out, [$d['email'],$d['name'],$d['company'],$d['dl_time']]);
    fclose($out); exit();
  }
  ?>

  <div class="stat-bar">
    <div class="sb"><div class="sb-n"><?= count($downloads) ?></div><div class="sb-l">Total Dispatches</div></div>
    <div class="sb"><div class="sb-n"><?= $unique ?></div><div class="sb-l">Unique Recipients</div></div>
  </div>

  <?php if (empty($downloads)): ?>
    <div class="empty">No report dispatches recorded yet.</div>
  <?php else: ?>
  <table>
    <thead><tr><th>Email</th><th>Name</th><th>Company</th><th>Downloaded</th></tr></thead>
    <tbody>
    <?php foreach ($downloads as $d): ?>
      <tr>
        <td><?= htmlspecialchars($d['email']) ?></td>
        <td><?= htmlspecialchars($d['name'] ?: '—') ?></td>
        <td><?= htmlspecialchars($d['company'] ?: '—') ?></td>
        <td style="color:#6a6058"><?= $d['dl_time'] ?></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  <?php endif; ?>
</div>
</body>
</html>
