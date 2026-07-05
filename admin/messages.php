<?php
require_once dirname(__DIR__) . '/api/helpers.php';
requireAdmin();
$pdo      = getDB();
$filter   = $_GET['status'] ?? 'all';
$where    = $filter !== 'all' ? "WHERE status = " . $pdo->quote($filter) : '';
$messages = $pdo->query("SELECT * FROM messages $where ORDER BY created_at DESC")->fetchAll();
$active = 'letters';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Correspondence — TRENDSHIP</title>
<link rel="stylesheet" href="admin.css">
</head>
<body class="admin-body">
<?php require __DIR__ . '/includes/sidebar.php'; ?>
<div class="main">
  <div class="top-bar">
    <div>
      <div class="page-eyebrow">Gather · Letters</div>
      <div class="page-title">Correspondence <span style="color:var(--muted);font-size:18px">(<?= count($messages) ?>)</span></div>
    </div>
    <div class="filter-pills">
      <a href="?status=all"      class="pill <?= $filter==='all'?'active':'' ?>">All</a>
      <a href="?status=unread"   class="pill <?= $filter==='unread'?'active':'' ?>">Unopened</a>
      <a href="?status=read"     class="pill <?= $filter==='read'?'active':'' ?>">Opened</a>
      <a href="?status=replied"  class="pill <?= $filter==='replied'?'active':'' ?>">Answered</a>
      <a href="?status=archived" class="pill <?= $filter==='archived'?'active':'' ?>">Archived</a>
    </div>
  </div>

  <?php if (empty($messages)): ?>
    <div class="empty">No correspondence received yet.</div>
  <?php else: ?>
  <div class="msg-list">
  <?php foreach ($messages as $m): ?>
    <div class="msg-card <?= $m['status']==='unread'?'unread':'' ?>" id="card-<?= $m['id'] ?>"
         onclick="viewMessage(<?= $m['id'] ?>, <?= htmlspecialchars(json_encode($m), ENT_QUOTES) ?>)">
      <div class="msg-head">
        <span class="msg-name"><?= htmlspecialchars($m['name']) ?></span>
        <span class="msg-meta">
          <span class="badge badge-<?= $m['interest'] ?>"><?= $m['interest'] ?></span>
          &nbsp;<?= date('d M Y H:i', strtotime($m['created_at'])) ?>
        </span>
      </div>
      <div class="msg-email"><?= htmlspecialchars($m['email']) ?> <?= $m['company']?'· '.htmlspecialchars($m['company']):'' ?></div>
      <div class="msg-preview"><?= htmlspecialchars($m['message']) ?></div>
      <div class="msg-actions" onclick="event.stopPropagation()">
        <select class="btn btn-ghost" onchange="updateMsgStatus(<?= $m['id'] ?>, this.value)" style="padding:5px 8px;cursor:pointer">
          <option <?= $m['status']==='unread'?'selected':'' ?>>unread</option>
          <option <?= $m['status']==='read'?'selected':'' ?>>read</option>
          <option <?= $m['status']==='replied'?'selected':'' ?>>replied</option>
          <option <?= $m['status']==='archived'?'selected':'' ?>>archived</option>
        </select>
        <a href="mailto:<?= htmlspecialchars($m['email']) ?>" class="btn btn-ghost">Compose Reply</a>
        <button class="btn btn-retire" onclick="deleteMsg(<?= $m['id'] ?>)">Retire</button>
      </div>
    </div>
  <?php endforeach; ?>
  </div>
  <?php endif; ?>
</div>

<!-- Message detail overlay -->
<div class="detail-overlay" id="detailOverlay">
  <div class="detail-box" style="position:relative">
    <button class="close-btn" onclick="closeDetail()">✕</button>
    <h3 id="d-name"></h3>
    <div class="detail-meta" id="d-meta"></div>
    <div class="detail-body" id="d-body"></div>
  </div>
</div>

<div class="toast" id="toast"></div>
<script>
function viewMessage(id, msg) {
  document.getElementById('d-name').textContent = msg.name;
  document.getElementById('d-meta').textContent =
    msg.email + (msg.company ? ' · ' + msg.company : '') +
    '  |  ' + msg.interest + '  |  ' + msg.created_at;
  document.getElementById('d-body').textContent = msg.message;
  document.getElementById('detailOverlay').classList.add('open');
  // Mark as read
  if (msg.status === 'unread') {
    updateMsgStatus(id, 'read');
    const card = document.getElementById('card-'+id);
    if (card) card.classList.remove('unread');
  }
}
function closeDetail() {
  document.getElementById('detailOverlay').classList.remove('open');
}
async function updateMsgStatus(id, status) {
  const res  = await fetch('../api/messages/update.php', {
    method:'PUT', headers:{'Content-Type':'application/json'},
    body: JSON.stringify({id, status})
  });
  const data = await res.json();
  showToast(data.message || data.error, !data.success);
}
async function deleteMsg(id) {
  if (!confirm('Retire this letter from the archive?')) return;
  const res  = await fetch('../api/messages/delete.php', {
    method:'DELETE', headers:{'Content-Type':'application/json'},
    body: JSON.stringify({id})
  });
  const data = await res.json();
  if (data.success) { document.getElementById('card-'+id)?.remove(); showToast(data.message); }
  else showToast(data.error, true);
}
function showToast(msg, err=false) {
  const t = document.getElementById('toast');
  t.textContent = msg;
  t.style.borderColor = err ? '#c05050' : '#d4857a';
  t.classList.add('show');
  setTimeout(() => t.classList.remove('show'), 3000);
}
document.getElementById('detailOverlay').addEventListener('click', e => {
  if (e.target === e.currentTarget) closeDetail();
});
</script>
</body>
</html>
