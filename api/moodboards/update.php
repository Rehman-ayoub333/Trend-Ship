<?php
require_once dirname(__DIR__) . '/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
  respond(['success' => false, 'error' => 'Method not allowed'], 405);
}

session_name(SESSION_NAME);
session_start();

$body      = getBody();
$sessionId = session_id();
$pdo       = getDB();

$stmt = $pdo->prepare("SELECT id FROM moodboards WHERE session_id = ? ORDER BY updated_at DESC LIMIT 1");
$stmt->execute([$sessionId]);
$mb   = $stmt->fetch();

if (!$mb) respond(['success' => false, 'error' => 'No moodboard found for this session'], 404);

$items = $body['items'] ?? null;
$name  = isset($body['name']) ? clean($body['name']) : null;

$sets   = [];
$params = [];

if ($items !== null) {
  if (count($items) > 6) respond(['success' => false, 'error' => 'Maximum 6 materials'], 422);
  $sets[]             = 'material_ids = :items';
  $params[':items']   = json_encode($items);
}
if ($name !== null) {
  $sets[]           = 'name = :name';
  $params[':name']  = $name;
}
if (empty($sets)) respond(['success' => false, 'error' => 'Nothing to update'], 400);

$params[':id'] = $mb['id'];
$pdo->prepare("UPDATE moodboards SET " . implode(', ', $sets) . " WHERE id = :id")
    ->execute($params);

respond(['success' => true, 'message' => 'Moodboard updated']);
