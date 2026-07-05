<?php
require_once dirname(__DIR__) . '/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
  respond(['success' => false, 'error' => 'Method not allowed'], 405);
}

$body   = getBody();
$email  = strtolower(trim($body['email'] ?? ''));
$status = $body['status'] ?? 'unsubscribed';

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  respond(['success' => false, 'error' => 'Valid email required'], 400);
}

$pdo  = getDB();
$stmt = $pdo->prepare("UPDATE subscribers SET status = ? WHERE email = ?");
$stmt->execute([$status, $email]);

if ($stmt->rowCount() === 0) {
  respond(['success' => false, 'error' => 'Email not found'], 404);
}

respond(['success' => true, 'message' => 'Subscription updated']);
