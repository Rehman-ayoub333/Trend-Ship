<?php
require_once dirname(__DIR__) . '/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
  respond(['success' => false, 'error' => 'Method not allowed'], 405);
}
requireAdminAPI();

$body = getBody();
$id   = (int)($body['id'] ?? 0);
if (!$id) respond(['success' => false, 'error' => 'id is required'], 400);

$pdo  = getDB();
$stmt = $pdo->prepare("SELECT id FROM materials WHERE id = ?");
$stmt->execute([$id]);
if (!$stmt->fetch()) respond(['success' => false, 'error' => 'Material not found'], 404);

// Build dynamic SET clause — only update fields that are sent
$allowed = ['name','code','theme','surface','finish','thickness','dimensions',
            'description','image_url','texture_url','room_url',
            'color_1','color_2','color_3','application','featured','sort_order'];
$sets    = [];
$params  = [];

foreach ($allowed as $field) {
  if (array_key_exists($field, $body)) {
    $sets[]           = "$field = :$field";
    $params[":$field"] = ($field === 'application')
      ? json_encode($body[$field])
      : clean((string)$body[$field]);
  }
}

if (empty($sets)) respond(['success' => false, 'error' => 'No fields to update'], 400);

$params[':id'] = $id;
$pdo->prepare("UPDATE materials SET " . implode(', ', $sets) . " WHERE id = :id")
    ->execute($params);

$pdo->prepare("INSERT INTO activity_log (admin_id, action, target_table, target_id, detail, ip)
               VALUES (?, 'material.update', 'materials', ?, ?, ?)")
    ->execute([$_SESSION['admin_id'], $id, json_encode($body), $_SERVER['REMOTE_ADDR']]);

respond(['success' => true, 'message' => 'Material updated']);
