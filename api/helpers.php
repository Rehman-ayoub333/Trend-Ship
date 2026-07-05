<?php
require_once __DIR__ . '/config.php';

// CORS + JSON headers for API routes only (admin HTML pages use helpers without these)
$isApiRoute = strpos(str_replace('\\', '/', $_SERVER['SCRIPT_FILENAME'] ?? ''), '/api/') !== false;
if ($isApiRoute) {
  header('Access-Control-Allow-Origin: http://localhost');
  header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
  header('Access-Control-Allow-Headers: Content-Type, Authorization');
  header('Content-Type: application/json; charset=UTF-8');
  if (($_SERVER['REQUEST_METHOD'] ?? '') === 'OPTIONS') {
    http_response_code(200);
    exit();
  }
}

/**
 * Send a JSON response and exit.
 * Always use this — never echo directly.
 */
function respond(array $data, int $code = 200): void {
  http_response_code($code);
  echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
  exit();
}

/**
 * Get and decode JSON body from the request.
 * Returns array or exits with 400 if body is missing/invalid.
 */
function getBody(): array {
  $raw = file_get_contents('php://input');
  if (empty($raw)) return [];
  $data = json_decode($raw, true);
  if (json_last_error() !== JSON_ERROR_NONE) {
    respond(['success' => false, 'error' => 'Invalid JSON body'], 400);
  }
  return $data;
}

/**
 * Validate required fields are present and non-empty.
 * $rules = ['field' => 'type']   type: string|email|int|date|enum
 */
function validate(array $data, array $rules): array {
  $errors = [];
  foreach ($rules as $field => $type) {
    $val = trim($data[$field] ?? '');
    if ($val === '') {
      $errors[$field] = "$field is required";
      continue;
    }
    if ($type === 'email' && !filter_var($val, FILTER_VALIDATE_EMAIL)) {
      $errors[$field] = "$field must be a valid email address";
    }
    if ($type === 'int' && !is_numeric($val)) {
      $errors[$field] = "$field must be a number";
    }
    if ($type === 'date' && !strtotime($val)) {
      $errors[$field] = "$field must be a valid date (YYYY-MM-DD)";
    }
  }
  return $errors;
}

/**
 * Generate a unique reference number.
 * e.g. generateRef('MAI') → "MAI-2025-0042"
 */
function generateRef(string $prefix, string $table): string {
  $pdo  = getDB();
  $year = date('Y');
  $stmt = $pdo->query("SELECT COUNT(*) FROM `$table`");
  $n    = (int)$stmt->fetchColumn() + 1;
  return $prefix . '-' . $year . '-' . str_pad($n, 4, '0', STR_PAD_LEFT);
}

/**
 * Sanitise a string for safe output (extra layer beyond PDO).
 */
function clean(string $str): string {
  return htmlspecialchars(strip_tags(trim($str)), ENT_QUOTES, 'UTF-8');
}

/**
 * Start admin session with secure cookie settings.
 */
function initAdminSession(): void {
  session_name(SESSION_NAME);
  if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
      'lifetime' => SESSION_LIFETIME,
      'path'     => '/',
      'httponly' => true,
      'samesite' => 'Lax',
    ]);
    session_start();
  }
}

/**
 * Check admin session is active. Redirect to login if not.
 * Call at top of every admin/*.php file.
 */
function requireAdmin(): void {
  initAdminSession();
  if (empty($_SESSION['admin_id'])) {
    header('Location: ' . BASE_URL . '/admin/login.html');
    exit();
  }
  // Expire session after inactivity
  $last = $_SESSION['admin_last_activity'] ?? 0;
  if ($last && (time() - $last) > SESSION_LIFETIME) {
    session_unset();
    session_destroy();
    header('Location: ' . BASE_URL . '/admin/login.html?expired=1');
    exit();
  }
  $_SESSION['admin_last_activity'] = time();
}

/**
 * Check admin is logged in for API routes.
 * Returns 401 JSON if not authenticated.
 */
function requireAdminAPI(): void {
  initAdminSession();
  if (empty($_SESSION['admin_id'])) {
    respond(['success' => false, 'error' => 'Unauthorised'], 401);
  }
  $last = $_SESSION['admin_last_activity'] ?? 0;
  if ($last && (time() - $last) > SESSION_LIFETIME) {
    session_unset();
    session_destroy();
    respond(['success' => false, 'error' => 'Session expired. Please sign in again.'], 401);
  }
  $_SESSION['admin_last_activity'] = time();
}
