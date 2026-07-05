<?php
/** @var string $active Current page key: overview|surfaces|visits|letters|circle|ledger */
$active = $active ?? '';
$nav = [
  'overview' => ['href' => 'index.php',       'icon' => '◉', 'label' => 'Dashboard'],
  'surfaces' => ['href' => 'materials.php',   'icon' => '◈', 'label' => 'Materials'],
  'visits'   => ['href' => 'bookings.php',    'icon' => '◷', 'label' => 'Bookings'],
  'letters'  => ['href' => 'messages.php',    'icon' => '◻', 'label' => 'Messages'],
  'circle'   => ['href' => 'subscribers.php', 'icon' => '◎', 'label' => 'Subscribers'],
  'ledger'   => ['href' => 'downloads.php',   'icon' => '↓', 'label' => 'Downloads'],
];
$user = htmlspecialchars($_SESSION['admin_username'] ?? 'Admin');
?>
<div class="admin-grain" aria-hidden="true"></div>
<aside class="sidebar">
  <a href="../index.html" class="s-logo">TRENDSHIP</a>
  <span class="s-tagline">Admin Panel · 2025</span>

  <div class="s-user">
    <span class="s-user-dot"></span>
    <span class="s-user-name"><?= $user ?></span>
  </div>

  <div class="s-label">Manage</div>
  <?php foreach ($nav as $key => $item): ?>
    <a href="<?= $item['href'] ?>" class="s-link <?= $active === $key ? 'active' : '' ?>">
      <span class="ic"><?= $item['icon'] ?></span> <?= $item['label'] ?>
    </a>
  <?php endforeach; ?>

  <div class="s-divider"></div>
  <a href="../index.html" class="s-link"><span class="ic">←</span> View Website</a>
  <a href="logout.php" class="signout">Sign Out</a>
</aside>
