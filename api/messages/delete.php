<?php
require_once dirname(__DIR__) . '/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
  respond(['success' => false, 'error' => 'Method not allowed'], 405);
}
requireAdminAPI();

$id  = (int)(getBody()['id'] ?? $_GET['id'] ?? 0);
if (!$id) respond(['success' => false, 'error' => 'id is required'], 400);

$pdo  = getDB();
$stmt = $pdo->prepare("SELECT name FROM messages WHERE id = ?");
$stmt->execute([$id]);
$row  = $stmt->fetch();
if (!$row) respond(['success' => false, 'error' => 'Message not found'], 404);

$pdo->prepare("DELETE FROM messages WHERE id = ?")->execute([$id]);
respond(['success' => true, 'message' => "Message from '{$row['name']}' deleted"]);
