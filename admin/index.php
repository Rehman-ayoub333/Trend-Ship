<?php
require_once dirname(__DIR__) . '/api/helpers.php';
requireAdmin();
$pdo = getDB();

$stats = [
  'materials'   => $pdo->query("SELECT COUNT(*) FROM materials")->fetchColumn(),
  'bookings'    => $pdo->query("SELECT COUNT(*) FROM bookings WHERE status='pending'")->fetchColumn(),
  'messages'    => $pdo->query("SELECT COUNT(*) FROM messages WHERE status='unread'")->fetchColumn(),
  'subscribers' => $pdo->query("SELECT COUNT(*) FROM subscribers WHERE status='active'")->fetchColumn(),
  'downloads'   => $pdo->query("SELECT COUNT(*) FROM report_downloads")->fetchColumn(),
];

$recent_messages = $pdo->query(
  "SELECT name, email, interest, created_at FROM messages ORDER BY created_at DESC LIMIT 5"
)->fetchAll();

$active = 'overview';
$user = htmlspecialchars($_SESSION['admin_username']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard — TRENDSHIP Admin</title>
<link rel="stylesheet" href="admin.css">
</head>
<body class="admin-body">
<?php require __DIR__ . '/includes/sidebar.php'; ?>
<main class="main">
  <header class="dash-hero">
    <div class="dash-hero-text">
      <div class="page-eyebrow">Welcome back</div>
      <div class="page-title">Dashboard</div>
      <p class="page-sub">Hello, <?= $user ?> — manage materials, messages, bookings and more.</p>
    </div>
    <div class="dash-hero-accent" aria-hidden="true"></div>
  </header>

  <div class="stat-grid">
    <a href="materials.php" class="stat-card stat-link">
      <div class="stat-n"><?= $stats['materials'] ?></div>
      <div class="stat-l">Materials</div>
    </a>
    <a href="bookings.php" class="stat-card stat-link">
      <div class="stat-n"><?= $stats['bookings'] ?></div>
      <div class="stat-l">Pending Bookings</div>
    </a>
    <a href="messages.php" class="stat-card stat-link">
      <div class="stat-n"><?= $stats['messages'] ?></div>
      <div class="stat-l">Unread Messages</div>
    </a>
    <a href="subscribers.php" class="stat-card stat-link">
      <div class="stat-n"><?= $stats['subscribers'] ?></div>
      <div class="stat-l">Subscribers</div>
    </a>
    <a href="downloads.php" class="stat-card stat-link">
      <div class="stat-n"><?= $stats['downloads'] ?></div>
      <div class="stat-l">Report Downloads</div>
    </a>
  </div>

  <div class="quick-grid">
    <a href="materials.php" class="quick-card">
      <span class="quick-icon">◈</span>
      <span class="quick-label">Materials</span>
      <span class="quick-hint">Add · Edit · Delete</span>
    </a>
    <a href="messages.php" class="quick-card">
      <span class="quick-icon">◻</span>
      <span class="quick-label">Messages</span>
      <span class="quick-hint">Read &amp; reply</span>
    </a>
    <a href="bookings.php" class="quick-card">
      <span class="quick-icon">◷</span>
      <span class="quick-label">Bookings</span>
      <span class="quick-hint">Visits &amp; events</span>
    </a>
    <a href="subscribers.php" class="quick-card">
      <span class="quick-icon">◎</span>
      <span class="quick-label">Subscribers</span>
      <span class="quick-hint">Newsletter list</span>
    </a>
  </div>

  <?php if ($recent_messages): ?>
  <div class="card">
    <div class="card-title">Recent Messages</div>
    <table>
      <thead><tr><th>Name</th><th>Email</th><th>Topic</th><th>Date</th><th></th></tr></thead>
      <tbody>
      <?php foreach ($recent_messages as $m): ?>
        <tr>
          <td><?= htmlspecialchars($m['name']) ?></td>
          <td><?= htmlspecialchars($m['email']) ?></td>
          <td><span class="badge badge-<?= $m['interest'] ?>"><?= $m['interest'] ?></span></td>
          <td><?= date('d M Y', strtotime($m['created_at'])) ?></td>
          <td><a href="messages.php" class="btn btn-ghost">View</a></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php endif; ?>
</main>
</body>
</html>
