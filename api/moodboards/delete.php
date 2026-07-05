<?php
require_once dirname(__DIR__) . '/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
  respond(['success' => false, 'error' => 'Method not allowed'], 405);
}

session_name(SESSION_NAME);
session_start();

$pdo  = getDB();
$stmt = $pdo->prepare("DELETE FROM moodboards WHERE session_id = ?");
$stmt->execute([session_id()]);

respond(['success' => true, 'message' => 'Moodboard cleared']);
