<?php
require_once dirname(__DIR__) . '/helpers.php';

$method = $_SERVER['REQUEST_METHOD'];
$pdo    = getDB();

// ── GET: admin views subscriber list ─────────────────────
if ($method === 'GET') {
  requireAdminAPI();
  $status = $_GET['status'] ?? 'active';
  $stmt   = $pdo->prepare(
    "SELECT * FROM subscribers WHERE status = ? ORDER BY subscribed_at DESC"
  );
  $stmt->execute([$status]);
  $rows = $stmt->fetchAll();
  respond(['success' => true, 'count' => count($rows), 'data' => $rows]);
}

// ── POST: visitor subscribes ──────────────────────────────
if ($method === 'POST') {
  $body   = getBody();
  $errors = validate($body, ['email' => 'email']);
  if ($errors) respond(['success' => false, 'errors' => $errors], 422);

  $email  = strtolower(trim($body['email']));
  $source = clean($body['source'] ?? 'newsletter');

  // Check already subscribed
  $check = $pdo->prepare("SELECT id, status FROM subscribers WHERE email = ?");
  $check->execute([$email]);
  $existing = $check->fetch();

  if ($existing) {
    if ($existing['status'] === 'active') {
      respond(['success' => false, 'error' => 'This email is already subscribed'], 409);
    }
    // Re-subscribe if they previously unsubscribed
    $pdo->prepare("UPDATE subscribers SET status='active', source=? WHERE email=?")
        ->execute([$source, $email]);
    respond(['success' => true, 'message' => 'Welcome back! You are now re-subscribed.']);
  }

  $pdo->prepare("INSERT INTO subscribers (email, name, source) VALUES (?, ?, ?)")
      ->execute([$email, clean($body['name'] ?? ''), $source]);

  respond(['success' => true, 'message' => 'Thank you for subscribing!'], 201);
}

respond(['success' => false, 'error' => 'Method not allowed'], 405);
