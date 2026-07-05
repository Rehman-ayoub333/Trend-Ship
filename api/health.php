<?php
/**
 * Lightweight health check — used by admin login page.
 */
header('Content-Type: application/json; charset=UTF-8');
header('Cache-Control: no-store');

require_once __DIR__ . '/config.php';

try {
  $pdo = getDB();
  $pdo->query('SELECT 1');
  $admin = (int) $pdo->query('SELECT COUNT(*) FROM admins')->fetchColumn();
  echo json_encode([
    'ok'    => true,
    'db'    => true,
    'admin' => $admin > 0,
  ]);
} catch (Throwable $e) {
  http_response_code(503);
  echo json_encode([
    'ok'    => false,
    'db'    => false,
    'error' => 'MySQL is not running. Start MySQL in XAMPP Control Panel.',
  ]);
}
