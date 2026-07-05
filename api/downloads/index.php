<?php
require_once dirname(__DIR__) . '/helpers.php';

$method = $_SERVER['REQUEST_METHOD'];
$pdo    = getDB();

// ── GET: admin sees download list ─────────────────────────
if ($method === 'GET') {
  requireAdminAPI();
  $stmt = $pdo->query("SELECT * FROM report_downloads ORDER BY downloaded_at DESC");
  $rows = $stmt->fetchAll();
  respond(['success' => true, 'count' => count($rows), 'data' => $rows]);
}

// ── POST: visitor requests download ──────────────────────
if ($method === 'POST') {
  $body   = getBody();
  $errors = validate($body, ['email' => 'email']);
  if ($errors) respond(['success' => false, 'errors' => $errors], 422);

  $email = strtolower(trim($body['email']));

  // Record the download
  $pdo->prepare("INSERT INTO report_downloads (email, name, company) VALUES (?,?,?)")
      ->execute([$email, clean($body['name'] ?? ''), clean($body['company'] ?? '')]);

  // Auto-subscribe with source = report_download
  $pdo->prepare(
    "INSERT IGNORE INTO subscribers (email, name, source) VALUES (?,?,'report_download')"
  )->execute([$email, clean($body['name'] ?? '')]);

  respond([
    'success'  => true,
    'pdf_url'  => BASE_URL . '/assets/TRENDSHIP-2025-Trend-Report.pdf',
    'message'  => 'Your download is ready!',
  ]);
}

respond(['success' => false, 'error' => 'Method not allowed'], 405);
