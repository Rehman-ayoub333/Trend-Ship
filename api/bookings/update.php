<?php
require_once dirname(__DIR__) . '/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
  respond(['success' => false, 'error' => 'Method not allowed'], 405);
}
requireAdminAPI();

$body   = getBody();
$id     = (int)($body['id'] ?? 0);
$status = $body['status'] ?? '';

if (!$id || !in_array($status, ['pending','confirmed','cancelled'])) {
  respond(['success' => false, 'error' => 'id and valid status required'], 400);
}

getDB()->prepare("UPDATE bookings SET status = ? WHERE id = ?")
       ->execute([$status, $id]);

respond(['success' => true, 'message' => "Booking status updated to $status"]);
