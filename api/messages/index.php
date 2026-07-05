<?php
require_once dirname(__DIR__) . '/helpers.php';

$method = $_SERVER['REQUEST_METHOD'];
$pdo    = getDB();

// ── GET: admin views all messages ─────────────────────────
if ($method === 'GET') {
  requireAdminAPI();
  $where  = '1=1';
  $params = [];
  if (!empty($_GET['status'])) {
    $where .= ' AND status = :status';
    $params[':status'] = $_GET['status'];
  }
  if (!empty($_GET['interest'])) {
    $where .= ' AND interest = :interest';
    $params[':interest'] = $_GET['interest'];
  }
  $stmt = $pdo->prepare(
    "SELECT * FROM messages WHERE $where ORDER BY created_at DESC"
  );
  $stmt->execute($params);
  respond(['success' => true, 'data' => $stmt->fetchAll()]);
}

// ── POST: visitor sends a contact message ─────────────────
if ($method === 'POST') {
  $body   = getBody();
  $errors = validate($body, [
    'name'    => 'string',
    'email'   => 'email',
    'message' => 'string',
  ]);
  if ($errors) respond(['success' => false, 'errors' => $errors], 422);

  // Rate limit: max 3 messages per email per 24 hours
  $rateCheck = $pdo->prepare(
    "SELECT COUNT(*) FROM messages WHERE email = ? AND created_at > NOW() - INTERVAL 24 HOUR"
  );
  $rateCheck->execute([strtolower(trim($body['email']))]);
  if ($rateCheck->fetchColumn() >= 3) {
    respond(['success' => false, 'error' => 'Too many messages. Please wait 24 hours.'], 429);
  }

  $ref  = generateRef('TRD', 'messages');
  $stmt = $pdo->prepare("
    INSERT INTO messages (ref, name, email, company, interest, message)
    VALUES (:ref,:name,:email,:company,:interest,:message)
  ");
  $stmt->execute([
    ':ref'      => $ref,
    ':name'     => clean($body['name']),
    ':email'    => strtolower(trim($body['email'])),
    ':company'  => clean($body['company'] ?? ''),
    ':interest' => $body['interest'] ?? 'general',
    ':message'  => clean($body['message']),
  ]);

  // Also add email to subscribers with source 'contact'
  $subCheck = $pdo->prepare("SELECT id FROM subscribers WHERE email = ?");
  $subCheck->execute([strtolower(trim($body['email']))]);
  if (!$subCheck->fetch()) {
    $pdo->prepare("INSERT IGNORE INTO subscribers (email, name, source) VALUES (?,?,?)")
        ->execute([strtolower(trim($body['email'])), clean($body['name']), 'contact']);
  }

  respond([
    'success'   => true,
    'ref'       => $ref,
    'message'   => "Thank you, {$body['name']}! Your reference is $ref. We'll reply within 2 business days.",
  ], 201);
}

respond(['success' => false, 'error' => 'Method not allowed'], 405);
