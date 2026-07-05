<?php
require_once dirname(__DIR__) . '/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
  respond(['success' => false, 'error' => 'Method not allowed'], 405);
}
requireAdminAPI();

$body = getBody();
$id   = (int)($body['id'] ?? $_GET['id'] ?? 0);
if (!$id) respond(['success' => false, 'error' => 'id is required'], 400);

$pdo  = getDB();
$stmt = $pdo->prepare("SELECT name FROM materials WHERE id = ?");
$stmt->execute([$id]);
$mat  = $stmt->fetch();
if (!$mat) respond(['success' => false, 'error' => 'Material not found'], 404);

$pdo->prepare("DELETE FROM materials WHERE id = ?")->execute([$id]);

$pdo->prepare("INSERT INTO activity_log (admin_id, action, target_table, target_id, detail, ip)
               VALUES (?, 'material.delete', 'materials', ?, ?, ?)")
    ->execute([$_SESSION['admin_id'], $id,
               json_encode(['deleted_name' => $mat['name']]),
               $_SERVER['REMOTE_ADDR']]);

respond(['success' => true, 'message' => "Material '{$mat['name']}' deleted"]);
