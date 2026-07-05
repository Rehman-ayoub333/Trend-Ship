<?php
require_once dirname(__DIR__) . '/helpers.php';

session_name(SESSION_NAME);
session_start();

$method    = $_SERVER['REQUEST_METHOD'];
$pdo       = getDB();
$sessionId = session_id();

// ── GET: load moodboard for this session ─────────────────
if ($method === 'GET') {
  $stmt = $pdo->prepare(
    "SELECT * FROM moodboards WHERE session_id = ? ORDER BY updated_at DESC LIMIT 1"
  );
  $stmt->execute([$sessionId]);
  $mb = $stmt->fetch();

  if (!$mb) {
    respond(['success' => true, 'data' => ['items' => [], 'name' => 'My Moodboard']]);
  }

  respond([
    'success' => true,
    'data'    => [
      'id'    => $mb['id'],
      'name'  => $mb['name'],
      'items' => json_decode($mb['material_ids'], true) ?? [],
    ],
  ]);
}

// ── POST: save/update moodboard ───────────────────────────
if ($method === 'POST') {
  $body  = getBody();
  $items = $body['items'] ?? [];
  $name  = clean($body['name'] ?? 'My Moodboard');
  $email = strtolower(trim($body['email'] ?? ''));

  if (count($items) > 6) {
    respond(['success' => false, 'error' => 'Maximum 6 materials per moodboard'], 422);
  }

  // Check if moodboard exists for this session
  $stmt = $pdo->prepare(
    "SELECT id FROM moodboards WHERE session_id = ? ORDER BY updated_at DESC LIMIT 1"
  );
  $stmt->execute([$sessionId]);
  $existing = $stmt->fetch();

  if ($existing) {
    $pdo->prepare(
      "UPDATE moodboards SET name=?, material_ids=?, email=? WHERE id=?"
    )->execute([$name, json_encode($items), $email ?: null, $existing['id']]);
    $id = $existing['id'];
  } else {
    $pdo->prepare(
      "INSERT INTO moodboards (session_id, name, material_ids, email) VALUES (?,?,?,?)"
    )->execute([$sessionId, $name, json_encode($items), $email ?: null]);
    $id = $pdo->lastInsertId();
  }

  respond(['success' => true, 'id' => $id, 'message' => 'Moodboard saved']);
}

respond(['success' => false, 'error' => 'Method not allowed'], 405);
