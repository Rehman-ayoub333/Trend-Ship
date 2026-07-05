<?php
require_once dirname(__DIR__) . '/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
  respond(['success' => false, 'error' => 'Method not allowed'], 405);
}
requireAdminAPI();

$body  = getBody();
$id    = (int)($body['id'] ?? 0);
$email = strtolower(trim($body['email'] ?? ''));

if (!$id && !$email) {
  respond(['success' => false, 'error' => 'id or email required'], 400);
}

$pdo  = getDB();
$sql  = $id ? "DELETE FROM subscribers WHERE id = ?" : "DELETE FROM subscribers WHERE email = ?";
$val  = $id ?: $email;
$stmt = $pdo->prepare($sql);
$stmt->execute([$val]);

if ($stmt->rowCount() === 0) {
  respond(['success' => false, 'error' => 'Subscriber not found'], 404);
}
respond(['success' => true, 'message' => 'Subscriber permanently deleted']);
