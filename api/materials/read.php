<?php
require_once dirname(__DIR__) . '/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
  respond(['success' => false, 'error' => 'Method not allowed'], 405);
}

$id = (int)($_GET['id'] ?? 0);
if (!$id) respond(['success' => false, 'error' => 'id is required'], 400);

$stmt = getDB()->prepare("SELECT * FROM materials WHERE id = ?");
$stmt->execute([$id]);
$mat  = $stmt->fetch();

if (!$mat) respond(['success' => false, 'error' => 'Material not found'], 404);

respond(['success' => true, 'data' => $mat]);
