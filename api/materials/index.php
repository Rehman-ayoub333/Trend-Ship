<?php
require_once dirname(__DIR__) . '/helpers.php';

$method = $_SERVER['REQUEST_METHOD'];
$pdo    = getDB();

// ── GET: fetch all materials (with optional filter) ───────
if ($method === 'GET') {
  $where  = '1=1';
  $params = [];

  // ?theme=boost|cosmos|ooparts|synergy
  if (!empty($_GET['theme'])) {
    $allowed = ['boost','cosmos','ooparts','synergy'];
    if (in_array($_GET['theme'], $allowed)) {
      $where .= ' AND theme = :theme';
      $params[':theme'] = $_GET['theme'];
    }
  }

  // ?featured=1  — only featured materials (home page)
  if (isset($_GET['featured']) && $_GET['featured'] === '1') {
    $where .= ' AND featured = 1';
  }

  // ?search=terracotta
  if (!empty($_GET['search'])) {
    $where .= ' AND (name LIKE :s OR code LIKE :s OR description LIKE :s)';
    $params[':s'] = '%' . $_GET['search'] . '%';
  }

  $stmt = $pdo->prepare(
    "SELECT * FROM materials WHERE $where ORDER BY sort_order ASC, id ASC"
  );
  $stmt->execute($params);
  $materials = $stmt->fetchAll();

  respond(['success' => true, 'count' => count($materials), 'data' => $materials]);
}

// ── POST: create a new material (admin only) ──────────────
if ($method === 'POST') {
  requireAdminAPI();
  $body   = getBody();
  $errors = validate($body, [
    'name'      => 'string',
    'code'      => 'string',
    'theme'     => 'string',
    'surface'   => 'string',
    'image_url' => 'string',
  ]);
  if ($errors) respond(['success' => false, 'errors' => $errors], 422);

  // Check code is unique
  $check = $pdo->prepare("SELECT id FROM materials WHERE code = ?");
  $check->execute([$body['code']]);
  if ($check->fetch()) {
    respond(['success' => false, 'error' => 'Material code already exists'], 409);
  }

  $stmt = $pdo->prepare("
    INSERT INTO materials
      (name, code, theme, surface, finish, thickness, dimensions, description,
       image_url, texture_url, room_url, color_1, color_2, color_3,
       application, featured, sort_order)
    VALUES
      (:name,:code,:theme,:surface,:finish,:thickness,:dimensions,:description,
       :image_url,:texture_url,:room_url,:color_1,:color_2,:color_3,
       :application,:featured,:sort_order)
  ");
  $stmt->execute([
    ':name'        => clean($body['name']),
    ':code'        => strtoupper(clean($body['code'])),
    ':theme'       => $body['theme'],
    ':surface'     => clean($body['surface']),
    ':finish'      => clean($body['finish'] ?? ''),
    ':thickness'   => clean($body['thickness'] ?? ''),
    ':dimensions'  => clean($body['dimensions'] ?? ''),
    ':description' => clean($body['description'] ?? ''),
    ':image_url'   => $body['image_url'],
    ':texture_url' => $body['texture_url'] ?? null,
    ':room_url'    => $body['room_url'] ?? null,
    ':color_1'     => $body['color_1'] ?? null,
    ':color_2'     => $body['color_2'] ?? null,
    ':color_3'     => $body['color_3'] ?? null,
    ':application' => json_encode($body['application'] ?? []),
    ':featured'    => (int)($body['featured'] ?? 0),
    ':sort_order'  => (int)($body['sort_order'] ?? 0),
  ]);

  $newId = $pdo->lastInsertId();

  // Log the action
  $pdo->prepare("INSERT INTO activity_log (admin_id, action, target_table, target_id, detail, ip)
                 VALUES (?, 'material.create', 'materials', ?, ?, ?)")
      ->execute([$_SESSION['admin_id'], $newId, json_encode($body), $_SERVER['REMOTE_ADDR']]);

  respond(['success' => true, 'id' => $newId, 'message' => 'Material created'], 201);
}

respond(['success' => false, 'error' => 'Method not allowed'], 405);
