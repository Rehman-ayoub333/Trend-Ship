<?php
require_once dirname(__DIR__) . '/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
  respond(['success' => false, 'error' => 'Method not allowed'], 405);
}
requireAdminAPI();

$id  = (int)($_GET['id'] ?? 0);
$ref = $_GET['ref'] ?? '';

if (!$id && !$ref) {
  respond(['success' => false, 'error' => 'id or ref required'], 400);
}

$pdo  = getDB();
$sql  = $id ? "SELECT * FROM bookings WHERE id = ?" : "SELECT * FROM bookings WHERE ref = ?";
$val  = $id ?: $ref;
$stmt = $pdo->prepare($sql);
$stmt->execute([$val]);
$row  = $stmt->fetch();

if (!$row) respond(['success' => false, 'error' => 'Booking not found'], 404);
respond(['success' => true, 'data' => $row]);
