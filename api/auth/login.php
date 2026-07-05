<?php
require_once dirname(__DIR__) . '/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  respond(['success' => false, 'error' => 'Method not allowed'], 405);
}

session_name(SESSION_NAME);
session_start();

$body     = getBody();
$username = trim($body['username'] ?? '');
$password = $body['password'] ?? '';

if (!$username || !$password) {
  respond(['success' => false, 'error' => 'Username and password required'], 400);
}

$pdo  = getDB();
$stmt = $pdo->prepare("SELECT id, password_hash FROM admins WHERE username = ?");
$stmt->execute([$username]);
$admin = $stmt->fetch();

if (!$admin || !password_verify($password, $admin['password_hash'])) {
  respond(['success' => false, 'error' => 'Invalid credentials'], 401);
}

$_SESSION['admin_id']       = $admin['id'];
$_SESSION['admin_username'] = $username;
$pdo->prepare("UPDATE admins SET last_login=NOW() WHERE id=?")->execute([$admin['id']]);

respond(['success' => true, 'message' => 'Logged in successfully']);
