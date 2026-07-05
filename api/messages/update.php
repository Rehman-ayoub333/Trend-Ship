<?php
require_once dirname(__DIR__) . '/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
  respond(['success' => false, 'error' => 'Method not allowed'], 405);
}
requireAdminAPI();

$body    = getBody();
$id      = (int)($body['id'] ?? 0);
$allowed = ['unread','read','replied','archived'];
$status  = $body['status'] ?? '';

if (!$id || !in_array($status, $allowed)) {
  respond(['success' => false, 'error' => 'id and valid status required'], 400);
}

getDB()->prepare("UPDATE messages SET status = ? WHERE id = ?")->execute([$status, $id]);
respond(['success' => true, 'message' => "Message status updated to $status"]);
