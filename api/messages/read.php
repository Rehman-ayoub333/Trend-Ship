<?php
require_once dirname(__DIR__) . '/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
  respond(['success' => false, 'error' => 'Method not allowed'], 405);
}
requireAdminAPI();

$id  = (int)($_GET['id'] ?? 0);
$pdo = getDB();

$stmt = $pdo->prepare("SELECT * FROM messages WHERE id = ?");
$stmt->execute([$id]);
$msg  = $stmt->fetch();
if (!$msg) respond(['success' => false, 'error' => 'Message not found'], 404);

// Auto-mark as read when viewed in admin
if ($msg['status'] === 'unread') {
  $pdo->prepare("UPDATE messages SET status = 'read' WHERE id = ?")->execute([$id]);
  $msg['status'] = 'read';
}

respond(['success' => true, 'data' => $msg]);
