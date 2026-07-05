<?php
/**
 * Login handler — POST only. Form UI is login.html.
 */
require_once dirname(__DIR__) . '/api/config.php';
require_once dirname(__DIR__) . '/api/helpers.php';
initAdminSession();

$loginPage = BASE_URL . '/admin/login.html';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  $qs = isset($_GET['expired']) ? '?expired=1' : '';
  header('Location: ' . $loginPage . $qs);
  exit();
}

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if ($username === '' || $password === '') {
  header('Location: ' . $loginPage . '?error=' . urlencode('Please enter your username and password.'));
  exit();
}

try {
  $pdo = getDB();
} catch (PDOException $e) {
  header('Location: ' . $loginPage . '?error=' . urlencode('Database offline. Open XAMPP Control Panel, start MySQL, then try again.'));
  exit();
}

$stmt = $pdo->prepare("SELECT id, password_hash FROM admins WHERE username = ?");
$stmt->execute([$username]);
$admin = $stmt->fetch();

if ($admin && password_verify($password, $admin['password_hash'])) {
  session_regenerate_id(true);
  $_SESSION['admin_id']            = $admin['id'];
  $_SESSION['admin_username']      = $username;
  $_SESSION['admin_last_activity'] = time();
  $pdo->prepare("UPDATE admins SET last_login=NOW() WHERE id=?")->execute([$admin['id']]);
  header('Location: ' . BASE_URL . '/admin/index.php');
  exit();
}

header('Location: ' . $loginPage . '?error=' . urlencode('Invalid username or password. Please try again.'));
exit();
