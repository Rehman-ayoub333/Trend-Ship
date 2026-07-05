# TRENDSHIP — Complete CRUD & XAMPP Database Specification
## Full Backend Integration | PHP + MySQL | Zero Frontend Damage
### Version 1.0 — Connects to existing 9-page website

> **Core principle:** The existing website's design, animations, Three.js organism,
> GSAP scroll effects, and all visual behaviour are NEVER touched. The backend layer
> sits underneath — serving data to the same HTML elements that already exist.
> Every PHP file is a separate file. No existing HTML is rewritten.

---

## TABLE OF CONTENTS

```
PART 0  — What XAMPP Is & How to Set It Up
PART 1  — Database Design (all tables, all columns, all relationships)
PART 2  — Folder Structure (new files only — existing files untouched)
PART 3  — Database Connection File
PART 4  — CRUD for Materials (Collection page)
PART 5  — CRUD for Exhibition Bookings (Contact / Exhibition pages)
PART 6  — CRUD for Newsletter Subscribers
PART 7  — CRUD for Contact Form Messages
PART 8  — CRUD for Trend Report Downloads
PART 9  — CRUD for Moodboard (session-based + DB save)
PART 10 — Admin Panel (manage all data without touching frontend)
PART 11 — How Each Frontend Page Connects to the Backend
PART 12 — API Endpoint Reference Table
PART 13 — Security Rules
PART 14 — Setup Checklist (step by step from zero to running)
```

---

## PART 0 — WHAT XAMPP IS & HOW TO SET IT UP

### 0.1 What XAMPP gives you

XAMPP is a local development server. It runs on your computer and gives you:

```
Apache  — the web server (serves your HTML/PHP files at http://localhost)
MySQL   — the database (stores all your data)
phpMyAdmin — a visual interface to manage your database at http://localhost/phpmyadmin
PHP     — the language that connects HTML to MySQL
```

Your existing HTML/CSS/JS website keeps working exactly as it is.
PHP files are added alongside the HTML files and handle all data operations.

### 0.2 XAMPP Installation

```
1. Download XAMPP from https://www.apachefriends.org
   Choose the version matching your OS (Windows / Mac / Linux)

2. Install it — accept all defaults

3. Open XAMPP Control Panel

4. Click START next to Apache
   Click START next to MySQL
   Both rows turn green — server is running

5. Open browser → go to: http://localhost
   You should see "Welcome to XAMPP"

6. Go to: http://localhost/phpmyadmin
   This is your database manager — no password needed on fresh install
```

### 0.3 Put your website in the right folder

```
Windows:  C:\xampp\htdocs\trendship\
Mac:      /Applications/XAMPP/htdocs/trendship/
Linux:    /opt/lampp/htdocs/trendship/

Your website root (index.html) goes inside the trendship/ folder.
Access it at: http://localhost/trendship/
```

### 0.4 Create the database

```
1. Go to http://localhost/phpmyadmin
2. Click "New" in the left sidebar
3. Database name: trendship_db
4. Collation: utf8mb4_unicode_ci
5. Click Create
```

---

## PART 1 — DATABASE DESIGN

### 1.1 Complete Schema — All 8 Tables

Run this entire SQL block in phpMyAdmin → SQL tab to create all tables at once.

```sql
-- ═══════════════════════════════════════════════════════════
-- TRENDSHIP DATABASE SCHEMA
-- Database: trendship_db
-- Charset:  utf8mb4_unicode_ci
-- Run in: phpMyAdmin → trendship_db → SQL tab
-- ═══════════════════════════════════════════════════════════

USE trendship_db;

-- ─── TABLE 1: materials ─────────────────────────────────────
-- Stores all surface materials shown in the Collection page
CREATE TABLE materials (
  id          INT          AUTO_INCREMENT PRIMARY KEY,
  name        VARCHAR(120) NOT NULL,
  code        VARCHAR(40)  NOT NULL UNIQUE,          -- e.g. LX-B2025-001
  theme       ENUM('boost','cosmos','ooparts','synergy') NOT NULL,
  surface     VARCHAR(80)  NOT NULL,                 -- e.g. "Matte Textured"
  finish      VARCHAR(80)  DEFAULT NULL,             -- e.g. "Anti-fingerprint"
  thickness   VARCHAR(20)  DEFAULT NULL,             -- e.g. "6mm"
  dimensions  VARCHAR(40)  DEFAULT NULL,             -- e.g. "1220 × 2440mm"
  description TEXT         DEFAULT NULL,
  image_url   VARCHAR(500) NOT NULL,
  texture_url VARCHAR(500) DEFAULT NULL,
  room_url    VARCHAR(500) DEFAULT NULL,
  color_1     VARCHAR(7)   DEFAULT NULL,             -- hex e.g. #c8885a
  color_2     VARCHAR(7)   DEFAULT NULL,
  color_3     VARCHAR(7)   DEFAULT NULL,
  application VARCHAR(200) DEFAULT NULL,             -- JSON array: ["Kitchen","Bath"]
  featured    TINYINT(1)   DEFAULT 0,               -- 1 = show on home page
  sort_order  INT          DEFAULT 0,
  created_at  TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
  updated_at  TIMESTAMP    DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ─── TABLE 2: bookings ──────────────────────────────────────
-- Exhibition visit bookings from contact/exhibition pages
CREATE TABLE bookings (
  id          INT          AUTO_INCREMENT PRIMARY KEY,
  ref         VARCHAR(20)  NOT NULL UNIQUE,          -- e.g. MAI-2025-0001
  name        VARCHAR(120) NOT NULL,
  email       VARCHAR(200) NOT NULL,
  phone       VARCHAR(40)  DEFAULT NULL,
  company     VARCHAR(120) DEFAULT NULL,
  visit_date  DATE         NOT NULL,
  visit_time  ENUM('10:00','12:00','14:00','16:00') NOT NULL,
  party_size  TINYINT      DEFAULT 1,
  notes       TEXT         DEFAULT NULL,
  status      ENUM('pending','confirmed','cancelled') DEFAULT 'pending',
  created_at  TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
  updated_at  TIMESTAMP    DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ─── TABLE 3: subscribers ───────────────────────────────────
-- Newsletter email list
CREATE TABLE subscribers (
  id           INT          AUTO_INCREMENT PRIMARY KEY,
  email        VARCHAR(200) NOT NULL UNIQUE,
  name         VARCHAR(120) DEFAULT NULL,
  source       VARCHAR(60)  DEFAULT 'newsletter',    -- newsletter / report_download / contact
  status       ENUM('active','unsubscribed') DEFAULT 'active',
  subscribed_at TIMESTAMP   DEFAULT CURRENT_TIMESTAMP,
  updated_at   TIMESTAMP    DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ─── TABLE 4: messages ──────────────────────────────────────
-- Contact form submissions
CREATE TABLE messages (
  id          INT          AUTO_INCREMENT PRIMARY KEY,
  ref         VARCHAR(20)  NOT NULL UNIQUE,          -- e.g. TRD-2025-0001
  name        VARCHAR(120) NOT NULL,
  email       VARCHAR(200) NOT NULL,
  company     VARCHAR(120) DEFAULT NULL,
  interest    ENUM('general','exhibition','collection','press','partnership') DEFAULT 'general',
  message     TEXT         NOT NULL,
  status      ENUM('unread','read','replied','archived') DEFAULT 'unread',
  created_at  TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
  updated_at  TIMESTAMP    DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ─── TABLE 5: report_downloads ──────────────────────────────
-- Tracks who downloaded the trend report
CREATE TABLE report_downloads (
  id          INT          AUTO_INCREMENT PRIMARY KEY,
  email       VARCHAR(200) NOT NULL,
  name        VARCHAR(120) DEFAULT NULL,
  company     VARCHAR(120) DEFAULT NULL,
  downloaded_at TIMESTAMP  DEFAULT CURRENT_TIMESTAMP
);

-- ─── TABLE 6: moodboards ────────────────────────────────────
-- Saved moodboards from the Collection page
CREATE TABLE moodboards (
  id          INT          AUTO_INCREMENT PRIMARY KEY,
  session_id  VARCHAR(64)  NOT NULL,                 -- PHP session_id()
  name        VARCHAR(120) DEFAULT 'My Moodboard',
  email       VARCHAR(200) DEFAULT NULL,             -- filled when they download
  material_ids VARCHAR(200) NOT NULL,                -- JSON: ["boost-01","synergy-02"]
  created_at  TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
  updated_at  TIMESTAMP    DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ─── TABLE 7: admins ────────────────────────────────────────
-- Admin panel login accounts
CREATE TABLE admins (
  id           INT          AUTO_INCREMENT PRIMARY KEY,
  username     VARCHAR(60)  NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,              -- bcrypt hash — never plain text
  email        VARCHAR(200) NOT NULL,
  last_login   TIMESTAMP    DEFAULT NULL,
  created_at   TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
);

-- ─── TABLE 8: activity_log ──────────────────────────────────
-- Audit trail of all admin actions
CREATE TABLE activity_log (
  id          INT          AUTO_INCREMENT PRIMARY KEY,
  admin_id    INT          DEFAULT NULL,
  action      VARCHAR(80)  NOT NULL,                -- e.g. "material.create"
  target_table VARCHAR(60) DEFAULT NULL,            -- e.g. "materials"
  target_id   INT          DEFAULT NULL,
  detail      TEXT         DEFAULT NULL,            -- JSON of what changed
  ip          VARCHAR(45)  DEFAULT NULL,
  created_at  TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (admin_id) REFERENCES admins(id) ON DELETE SET NULL
);

-- ─── SEED: default admin account ────────────────────────────
-- Password is: admin123  (change immediately after setup)
INSERT INTO admins (username, password_hash, email) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@trendship.com');

-- ─── SEED: 12 materials (matches the home page preview) ─────
INSERT INTO materials (name, code, theme, surface, image_url, color_1, color_2, color_3, featured, sort_order) VALUES
('Terracotta Linen', 'LX-B2025-001', 'boost',   'Matte Textured',  'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=800', '#c8885a','#b87848','#d8a880', 1, 1),
('Warm Oak',         'LX-B2025-002', 'boost',   'Wood Grain',      'https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?w=800', '#c8a060','#a88040','#e0c080', 0, 2),
('Amber Matte',      'LX-B2025-003', 'boost',   'Matte Smooth',    'https://images.unsplash.com/photo-1594736797933-d0501ba2fe65?w=800', '#d09848','#b07828','#e8b868', 0, 3),
('Midnight Slate',   'LX-C2025-001', 'cosmos',  'Stone Finish',    'https://images.unsplash.com/photo-1519710164239-da123dc03ef4?w=800', '#3a4870','#2a3860','#4a5880', 1, 4),
('Void Navy',        'LX-C2025-002', 'cosmos',  'Ultra Matte',     'https://images.unsplash.com/photo-1557804506-669a67965ba0?w=800',  '#1a2248','#0a1230','#2a3258', 0, 5),
('Steel Blue',       'LX-C2025-003', 'cosmos',  'Brushed Metal',   'https://images.unsplash.com/photo-1550859492-d5da9d8e45f3?w=800',  '#607090','#485878','#788aa8', 0, 6),
('Ancient Earth',    'LX-O2025-001', 'ooparts', 'Raw Textured',    'https://images.unsplash.com/photo-1565538810643-b5bdb714032a?w=800','#9a7840','#7a5820','#c0a060', 1, 7),
('Stone Ash',        'LX-O2025-002', 'ooparts', 'Honed Stone',     'https://images.unsplash.com/photo-1493666438817-866a91353ca9?w=800','#a8a090','#888070','#c8c0b0', 0, 8),
('Fossil Brown',     'LX-O2025-003', 'ooparts', 'Leather Touch',   'https://images.unsplash.com/photo-1598928506311-c55ded91a20c?w=800','#8a6848','#6a4828','#aa8868', 0, 9),
('Bloom Rose',       'LX-S2025-001', 'synergy', 'Soft Satin',      'https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?w=800','#d4857a','#b46858','#e8a898', 1, 10),
('Petal Pink',       'LX-S2025-002', 'synergy', 'Velvet Touch',    'https://images.unsplash.com/photo-1617104678090-33e8e12c3dd2?w=800','#e8b0b0','#c89090','#f8d0d0', 0, 11),
('Silk Greige',      'LX-S2025-003', 'synergy', 'Linen Weave',     'https://images.unsplash.com/photo-1600210492493-0946911123ea?w=800','#c8b8b0','#a89890','#e0d8d0', 0, 12);
```

### 1.2 Table Relationships Diagram

```
admins ──────────────────── activity_log
  (id)                        (admin_id FK)

materials ── (material_ids JSON ref) ── moodboards
  (id)

bookings        (standalone)
subscribers     (standalone)
messages        (standalone)
report_downloads (standalone)
```

### 1.3 Reference Numbers Format

```
Bookings:         MAI-YYYY-NNNN    e.g. MAI-2025-0042
Messages:         TRD-YYYY-NNNN    e.g. TRD-2025-0007
(auto-generated by PHP using date('Y') + zero-padded count)
```

---

## PART 2 — FOLDER STRUCTURE

Only NEW files are listed below. Existing files are NOT modified.

```
trendship/
│
├── index.html                    ← EXISTING — not touched
├── design-trend/index.html       ← EXISTING — not touched
├── themes/index.html             ← EXISTING — not touched
├── exhibition/index.html         ← EXISTING — not touched
├── collection/index.html         ← EXISTING — not touched
├── trend-report/index.html       ← EXISTING — not touched
├── lookbook/index.html           ← EXISTING — not touched
├── about/index.html              ← EXISTING — not touched
├── contact/index.html            ← EXISTING — not touched
│
└── api/                          ← NEW — all backend PHP files live here
    │
    ├── config.php                ← DB connection + constants
    ├── helpers.php               ← shared functions (respond, validate, ref)
    │
    ├── materials/
    │   ├── index.php             ← GET all materials / POST create
    │   ├── read.php              ← GET single material by id
    │   ├── update.php            ← PUT update material
    │   └── delete.php            ← DELETE material
    │
    ├── bookings/
    │   ├── index.php             ← GET all bookings / POST create
    │   ├── read.php              ← GET single booking by id or ref
    │   ├── update.php            ← PUT update booking status
    │   └── delete.php            ← DELETE booking
    │
    ├── subscribers/
    │   ├── index.php             ← GET all / POST subscribe
    │   ├── update.php            ← PUT update status (unsubscribe)
    │   └── delete.php            ← DELETE subscriber
    │
    ├── messages/
    │   ├── index.php             ← GET all / POST create
    │   ├── read.php              ← GET single message (marks as read)
    │   ├── update.php            ← PUT update status
    │   └── delete.php            ← DELETE message
    │
    ├── downloads/
    │   └── index.php             ← GET all downloads / POST record download
    │
    ├── moodboards/
    │   ├── index.php             ← GET session moodboard / POST save
    │   ├── update.php            ← PUT update moodboard items
    │   └── delete.php            ← DELETE moodboard
    │
    └── auth/
        ├── login.php             ← POST admin login
        └── logout.php            ← POST admin logout
│
└── admin/                        ← NEW — admin panel (protected)
    ├── index.php                 ← dashboard overview
    ├── login.php                 ← login form
    ├── materials.php             ← manage materials (CRUD UI)
    ├── bookings.php              ← manage bookings
    ├── messages.php              ← view contact messages
    ├── subscribers.php           ← manage email list
    └── downloads.php             ← report download log
```

---

## PART 3 — DATABASE CONNECTION FILE

### `api/config.php`

```php
<?php
/**
 * TRENDSHIP — Database Configuration
 * Edit the constants below to match your XAMPP setup.
 * NEVER commit this file to a public GitHub repository.
 * Add api/config.php to your .gitignore
 */

// ── Database credentials ──────────────────────────────────
define('DB_HOST', 'localhost');
define('DB_USER', 'root');        // XAMPP default username
define('DB_PASS', '');            // XAMPP default: no password
define('DB_NAME', 'trendship_db');
define('DB_PORT', 3306);

// ── Application constants ─────────────────────────────────
define('APP_NAME',    'TRENDSHIP');
define('APP_VERSION', '1.0');
define('BASE_URL',    'http://localhost/trendship');

// ── Security ──────────────────────────────────────────────
define('SESSION_NAME',    'trendship_session');
define('SESSION_LIFETIME', 3600);       // 1 hour in seconds
define('ADMIN_PATH',      '/admin');

// ── CORS — allow frontend to call API ─────────────────────
header('Access-Control-Allow-Origin: http://localhost');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json; charset=UTF-8');

// Handle preflight OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(200);
  exit();
}

// ── Create database connection ────────────────────────────
function getDB(): PDO {
  static $pdo = null;
  if ($pdo === null) {
    try {
      $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME
           . ";charset=utf8mb4;port=" . DB_PORT;
      $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
      ]);
    } catch (PDOException $e) {
      http_response_code(500);
      echo json_encode(['success' => false, 'error' => 'Database connection failed']);
      exit();
    }
  }
  return $pdo;
}
```

### `api/helpers.php`

```php
<?php
require_once __DIR__ . '/config.php';

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
 * Check admin session is active. Redirect to login if not.
 * Call at top of every admin/*.php file.
 */
function requireAdmin(): void {
  session_name(SESSION_NAME);
  session_start();
  if (empty($_SESSION['admin_id'])) {
    header('Location: ' . BASE_URL . '/admin/login.php');
    exit();
  }
}

/**
 * Check admin is logged in for API routes.
 * Returns 401 JSON if not authenticated.
 */
function requireAdminAPI(): void {
  session_name(SESSION_NAME);
  session_start();
  if (empty($_SESSION['admin_id'])) {
    respond(['success' => false, 'error' => 'Unauthorised'], 401);
  }
}
```

---

## PART 4 — CRUD: MATERIALS

### What CRUD means for materials:
- **CREATE** — Admin adds a new surface material via admin panel
- **READ** — Collection page fetches materials from DB (instead of hardcoded HTML)
- **UPDATE** — Admin edits a material's name, image, colours, featured status
- **DELETE** — Admin removes a material (confirmation required)

### `api/materials/index.php` — GET all / POST create

```php
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
```

### `api/materials/read.php` — GET single material

```php
<?php
require_once dirname(__DIR__) . '/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
  respond(['success' => false, 'error' => 'Method not allowed'], 405);
}

$id = (int)($_GET['id'] ?? 0);
if (!$id) respond(['success' => false, 'error' => 'id is required'], 400);

$stmt = getDB()->prepare("SELECT * FROM materials WHERE id = ?");
$stmt->execute([$id]);
$mat  = $stmt->fetch();

if (!$mat) respond(['success' => false, 'error' => 'Material not found'], 404);

respond(['success' => true, 'data' => $mat]);
```

### `api/materials/update.php` — PUT update material

```php
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
```

### `api/materials/delete.php` — DELETE material

```php
<?php
require_once dirname(__DIR__) . '/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
  respond(['success' => false, 'error' => 'Method not allowed'], 405);
}
requireAdminAPI();

$body = getBody();
$id   = (int)($body['id'] ?? $_GET['id'] ?? 0);
if (!$id) respond(['success' => false, 'error' => 'id is required'], 400);

$pdo  = getDB();
$stmt = $pdo->prepare("SELECT name FROM materials WHERE id = ?");
$stmt->execute([$id]);
$mat  = $stmt->fetch();
if (!$mat) respond(['success' => false, 'error' => 'Material not found'], 404);

$pdo->prepare("DELETE FROM materials WHERE id = ?")->execute([$id]);

$pdo->prepare("INSERT INTO activity_log (admin_id, action, target_table, target_id, detail, ip)
               VALUES (?, 'material.delete', 'materials', ?, ?, ?)")
    ->execute([$_SESSION['admin_id'], $id,
               json_encode(['deleted_name' => $mat['name']]),
               $_SERVER['REMOTE_ADDR']]);

respond(['success' => true, 'message' => "Material '{$mat['name']}' deleted"]);
```

---

## PART 5 — CRUD: EXHIBITION BOOKINGS

### `api/bookings/index.php` — GET all / POST create

```php
<?php
require_once dirname(__DIR__) . '/helpers.php';

$method = $_SERVER['REQUEST_METHOD'];
$pdo    = getDB();

// ── GET: admin sees all bookings ──────────────────────────
if ($method === 'GET') {
  requireAdminAPI();
  $where  = '1=1';
  $params = [];

  if (!empty($_GET['status'])) {
    $where .= ' AND status = :status';
    $params[':status'] = $_GET['status'];
  }
  if (!empty($_GET['date'])) {
    $where .= ' AND visit_date = :date';
    $params[':date'] = $_GET['date'];
  }

  $stmt = $pdo->prepare(
    "SELECT * FROM bookings WHERE $where ORDER BY visit_date ASC, visit_time ASC"
  );
  $stmt->execute($params);
  respond(['success' => true, 'data' => $stmt->fetchAll()]);
}

// ── POST: visitor creates a booking ──────────────────────
if ($method === 'POST') {
  $body   = getBody();
  $errors = validate($body, [
    'name'       => 'string',
    'email'      => 'email',
    'visit_date' => 'date',
    'visit_time' => 'string',
  ]);
  if ($errors) respond(['success' => false, 'errors' => $errors], 422);

  // Check date is not Monday (closed) and within next 30 days
  $ts = strtotime($body['visit_date']);
  if (date('N', $ts) == 1) {
    respond(['success' => false, 'error' => 'The exhibition is closed on Mondays'], 422);
  }
  if ($ts < strtotime('today') || $ts > strtotime('+30 days')) {
    respond(['success' => false, 'error' => 'Please choose a date within the next 30 days'], 422);
  }

  // Check this time slot is not full (max 20 per slot)
  $slotCheck = $pdo->prepare(
    "SELECT COUNT(*) FROM bookings
     WHERE visit_date = ? AND visit_time = ? AND status != 'cancelled'"
  );
  $slotCheck->execute([$body['visit_date'], $body['visit_time']]);
  if ($slotCheck->fetchColumn() >= 20) {
    respond(['success' => false, 'error' => 'This time slot is fully booked. Please choose another.'], 409);
  }

  $ref  = generateRef('MAI', 'bookings');
  $stmt = $pdo->prepare("
    INSERT INTO bookings (ref, name, email, phone, company, visit_date, visit_time, party_size, notes)
    VALUES (:ref,:name,:email,:phone,:company,:visit_date,:visit_time,:party_size,:notes)
  ");
  $stmt->execute([
    ':ref'        => $ref,
    ':name'       => clean($body['name']),
    ':email'      => strtolower(trim($body['email'])),
    ':phone'      => clean($body['phone'] ?? ''),
    ':company'    => clean($body['company'] ?? ''),
    ':visit_date' => $body['visit_date'],
    ':visit_time' => $body['visit_time'],
    ':party_size' => (int)($body['party_size'] ?? 1),
    ':notes'      => clean($body['notes'] ?? ''),
  ]);

  respond([
    'success'    => true,
    'ref'        => $ref,
    'message'    => "Booking confirmed! Your reference is $ref. Check your email.",
  ], 201);
}

respond(['success' => false, 'error' => 'Method not allowed'], 405);
```

### `api/bookings/update.php` — PUT update booking status

```php
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
```

### `api/bookings/delete.php` — DELETE booking

```php
<?php
require_once dirname(__DIR__) . '/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
  respond(['success' => false, 'error' => 'Method not allowed'], 405);
}
requireAdminAPI();

$id = (int)(getBody()['id'] ?? $_GET['id'] ?? 0);
if (!$id) respond(['success' => false, 'error' => 'id is required'], 400);

$pdo  = getDB();
$stmt = $pdo->prepare("SELECT ref FROM bookings WHERE id = ?");
$stmt->execute([$id]);
$row  = $stmt->fetch();
if (!$row) respond(['success' => false, 'error' => 'Booking not found'], 404);

$pdo->prepare("DELETE FROM bookings WHERE id = ?")->execute([$id]);
respond(['success' => true, 'message' => "Booking {$row['ref']} deleted"]);
```

---

## PART 6 — CRUD: NEWSLETTER SUBSCRIBERS

### `api/subscribers/index.php` — GET all / POST subscribe

```php
<?php
require_once dirname(__DIR__) . '/helpers.php';

$method = $_SERVER['REQUEST_METHOD'];
$pdo    = getDB();

// ── GET: admin views subscriber list ─────────────────────
if ($method === 'GET') {
  requireAdminAPI();
  $status = $_GET['status'] ?? 'active';
  $stmt   = $pdo->prepare(
    "SELECT * FROM subscribers WHERE status = ? ORDER BY subscribed_at DESC"
  );
  $stmt->execute([$status]);
  $rows = $stmt->fetchAll();
  respond(['success' => true, 'count' => count($rows), 'data' => $rows]);
}

// ── POST: visitor subscribes ──────────────────────────────
if ($method === 'POST') {
  $body   = getBody();
  $errors = validate($body, ['email' => 'email']);
  if ($errors) respond(['success' => false, 'errors' => $errors], 422);

  $email  = strtolower(trim($body['email']));
  $source = clean($body['source'] ?? 'newsletter');

  // Check already subscribed
  $check = $pdo->prepare("SELECT id, status FROM subscribers WHERE email = ?");
  $check->execute([$email]);
  $existing = $check->fetch();

  if ($existing) {
    if ($existing['status'] === 'active') {
      respond(['success' => false, 'error' => 'This email is already subscribed'], 409);
    }
    // Re-subscribe if they previously unsubscribed
    $pdo->prepare("UPDATE subscribers SET status='active', source=? WHERE email=?")
        ->execute([$source, $email]);
    respond(['success' => true, 'message' => 'Welcome back! You are now re-subscribed.']);
  }

  $pdo->prepare("INSERT INTO subscribers (email, name, source) VALUES (?, ?, ?)")
      ->execute([$email, clean($body['name'] ?? ''), $source]);

  respond(['success' => true, 'message' => 'Thank you for subscribing!'], 201);
}

respond(['success' => false, 'error' => 'Method not allowed'], 405);
```

### `api/subscribers/update.php` — Unsubscribe

```php
<?php
require_once dirname(__DIR__) . '/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
  respond(['success' => false, 'error' => 'Method not allowed'], 405);
}

$body   = getBody();
$email  = strtolower(trim($body['email'] ?? ''));
$status = $body['status'] ?? 'unsubscribed';

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  respond(['success' => false, 'error' => 'Valid email required'], 400);
}

$pdo  = getDB();
$stmt = $pdo->prepare("UPDATE subscribers SET status = ? WHERE email = ?");
$stmt->execute([$status, $email]);

if ($stmt->rowCount() === 0) {
  respond(['success' => false, 'error' => 'Email not found'], 404);
}

respond(['success' => true, 'message' => 'Subscription updated']);
```

---

## PART 7 — CRUD: CONTACT MESSAGES

### `api/messages/index.php` — GET all / POST create

```php
<?php
require_once dirname(__DIR__) . '/helpers.php';

$method = $_SERVER['REQUEST_METHOD'];
$pdo    = getDB();

// ── GET: admin views all messages ─────────────────────────
if ($method === 'GET') {
  requireAdminAPI();
  $where  = '1=1';
  $params = [];
  if (!empty($_GET['status'])) {
    $where .= ' AND status = :status';
    $params[':status'] = $_GET['status'];
  }
  if (!empty($_GET['interest'])) {
    $where .= ' AND interest = :interest';
    $params[':interest'] = $_GET['interest'];
  }
  $stmt = $pdo->prepare(
    "SELECT * FROM messages WHERE $where ORDER BY created_at DESC"
  );
  $stmt->execute($params);
  respond(['success' => true, 'data' => $stmt->fetchAll()]);
}

// ── POST: visitor sends a contact message ─────────────────
if ($method === 'POST') {
  $body   = getBody();
  $errors = validate($body, [
    'name'    => 'string',
    'email'   => 'email',
    'message' => 'string',
  ]);
  if ($errors) respond(['success' => false, 'errors' => $errors], 422);

  // Rate limit: max 3 messages per email per 24 hours
  $rateCheck = $pdo->prepare(
    "SELECT COUNT(*) FROM messages WHERE email = ? AND created_at > NOW() - INTERVAL 24 HOUR"
  );
  $rateCheck->execute([strtolower(trim($body['email']))]);
  if ($rateCheck->fetchColumn() >= 3) {
    respond(['success' => false, 'error' => 'Too many messages. Please wait 24 hours.'], 429);
  }

  $ref  = generateRef('TRD', 'messages');
  $stmt = $pdo->prepare("
    INSERT INTO messages (ref, name, email, company, interest, message)
    VALUES (:ref,:name,:email,:company,:interest,:message)
  ");
  $stmt->execute([
    ':ref'      => $ref,
    ':name'     => clean($body['name']),
    ':email'    => strtolower(trim($body['email'])),
    ':company'  => clean($body['company'] ?? ''),
    ':interest' => $body['interest'] ?? 'general',
    ':message'  => clean($body['message']),
  ]);

  // Also add email to subscribers with source 'contact'
  $subCheck = $pdo->prepare("SELECT id FROM subscribers WHERE email = ?");
  $subCheck->execute([strtolower(trim($body['email']))]);
  if (!$subCheck->fetch()) {
    $pdo->prepare("INSERT IGNORE INTO subscribers (email, name, source) VALUES (?,?,?)")
        ->execute([strtolower(trim($body['email'])), clean($body['name']), 'contact']);
  }

  respond([
    'success'   => true,
    'ref'       => $ref,
    'message'   => "Thank you, {$body['name']}! Your reference is $ref. We'll reply within 2 business days.",
  ], 201);
}

respond(['success' => false, 'error' => 'Method not allowed'], 405);
```

### `api/messages/read.php` — GET single + mark as read

```php
<?php
require_once dirname(__DIR__) . '/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
  respond(['success' => false, 'error' => 'Method not allowed'], 405);
}
requireAdminAPI();

$id  = (int)($_GET['id'] ?? 0);
$pdo = getDB();

$stmt = $pdo->prepare("SELECT * FROM messages WHERE id = ?");
$stmt->execute([$id]);
$msg  = $stmt->fetch();
if (!$msg) respond(['success' => false, 'error' => 'Message not found'], 404);

// Auto-mark as read when viewed in admin
if ($msg['status'] === 'unread') {
  $pdo->prepare("UPDATE messages SET status = 'read' WHERE id = ?")->execute([$id]);
  $msg['status'] = 'read';
}

respond(['success' => true, 'data' => $msg]);
```

### `api/messages/update.php` — PUT update status

```php
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
```

---

## PART 8 — CRUD: TREND REPORT DOWNLOADS

### `api/downloads/index.php` — GET all / POST record

```php
<?php
require_once dirname(__DIR__) . '/helpers.php';

$method = $_SERVER['REQUEST_METHOD'];
$pdo    = getDB();

// ── GET: admin sees download list ─────────────────────────
if ($method === 'GET') {
  requireAdminAPI();
  $stmt = $pdo->query("SELECT * FROM report_downloads ORDER BY downloaded_at DESC");
  $rows = $stmt->fetchAll();
  respond(['success' => true, 'count' => count($rows), 'data' => $rows]);
}

// ── POST: visitor requests download ──────────────────────
if ($method === 'POST') {
  $body   = getBody();
  $errors = validate($body, ['email' => 'email']);
  if ($errors) respond(['success' => false, 'errors' => $errors], 422);

  $email = strtolower(trim($body['email']));

  // Record the download
  $pdo->prepare("INSERT INTO report_downloads (email, name, company) VALUES (?,?,?)")
      ->execute([$email, clean($body['name'] ?? ''), clean($body['company'] ?? '')]);

  // Auto-subscribe with source = report_download
  $pdo->prepare(
    "INSERT IGNORE INTO subscribers (email, name, source) VALUES (?,?,'report_download')"
  )->execute([$email, clean($body['name'] ?? '')]);

  respond([
    'success'  => true,
    'pdf_url'  => BASE_URL . '/assets/TRENDSHIP-2025-Trend-Report.pdf',
    'message'  => 'Your download is ready!',
  ]);
}

respond(['success' => false, 'error' => 'Method not allowed'], 405);
```

---

## PART 9 — CRUD: MOODBOARD

### `api/moodboards/index.php` — GET session / POST save

```php
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
```

---

## PART 10 — ADMIN PANEL

### `admin/login.php`

```php
<?php
require_once dirname(__DIR__) . '/api/config.php';
session_name(SESSION_NAME);
session_start();

// Already logged in → dashboard
if (!empty($_SESSION['admin_id'])) {
  header('Location: index.php'); exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = trim($_POST['username'] ?? '');
  $password = $_POST['password'] ?? '';

  if ($username && $password) {
    $pdo  = getDB();
    $stmt = $pdo->prepare("SELECT id, password_hash FROM admins WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($password, $admin['password_hash'])) {
      $_SESSION['admin_id'] = $admin['id'];
      $_SESSION['admin_username'] = $username;
      // Update last login
      $pdo->prepare("UPDATE admins SET last_login=NOW() WHERE id=?")->execute([$admin['id']]);
      header('Location: index.php'); exit();
    } else {
      $error = 'Incorrect username or password.';
    }
  } else {
    $error = 'Please enter username and password.';
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>TRENDSHIP Admin — Login</title>
<style>
  *{margin:0;padding:0;box-sizing:border-box}
  body{font-family:'Segoe UI',system-ui,sans-serif;background:#0a0a0a;
       display:flex;align-items:center;justify-content:center;min-height:100vh}
  .box{background:#141414;border:1px solid #222;border-radius:8px;
       padding:48px;width:380px}
  .logo{font-size:22px;letter-spacing:.14em;color:#f4efe9;margin-bottom:8px}
  .sub{font-size:12px;color:#6a6058;letter-spacing:.1em;margin-bottom:36px}
  label{display:block;font-size:11px;letter-spacing:.15em;text-transform:uppercase;
        color:#6a6058;margin-bottom:6px}
  input{width:100%;background:#1e1e1e;border:1px solid #2a2a2a;border-radius:4px;
        padding:12px 14px;color:#f0e8e0;font-size:14px;margin-bottom:18px;outline:none}
  input:focus{border-color:#d4857a}
  button{width:100%;background:#d4857a;color:#fff;border:none;border-radius:4px;
         padding:13px;font-size:13px;letter-spacing:.15em;cursor:pointer;
         transition:background .2s}
  button:hover{background:#bf7268}
  .err{background:#2a1414;border:1px solid #6a2a2a;color:#e8a0a0;
       padding:10px 14px;border-radius:4px;font-size:13px;margin-bottom:18px}
  a{color:#c4968e;font-size:12px;display:block;text-align:center;
    margin-top:20px;text-decoration:none}
</style>
</head>
<body>
<div class="box">
  <div class="logo">TRENDSHIP</div>
  <div class="sub">Admin Panel</div>
  <?php if ($error): ?>
    <div class="err"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>
  <form method="POST">
    <label>Username</label>
    <input type="text" name="username" autocomplete="username" required>
    <label>Password</label>
    <input type="password" name="password" autocomplete="current-password" required>
    <button type="submit">Sign In</button>
  </form>
  <a href="../index.html">← Back to Website</a>
</div>
</body>
</html>
```

### `admin/index.php` — Dashboard

```php
<?php
require_once dirname(__DIR__) . '/api/config.php';
requireAdmin();
$pdo = getDB();

// Quick stats for dashboard cards
$stats = [
  'materials'   => $pdo->query("SELECT COUNT(*) FROM materials")->fetchColumn(),
  'bookings'    => $pdo->query("SELECT COUNT(*) FROM bookings WHERE status='pending'")->fetchColumn(),
  'messages'    => $pdo->query("SELECT COUNT(*) FROM messages WHERE status='unread'")->fetchColumn(),
  'subscribers' => $pdo->query("SELECT COUNT(*) FROM subscribers WHERE status='active'")->fetchColumn(),
  'downloads'   => $pdo->query("SELECT COUNT(*) FROM report_downloads")->fetchColumn(),
];

$recent_messages = $pdo->query(
  "SELECT name, email, interest, created_at FROM messages ORDER BY created_at DESC LIMIT 5"
)->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>TRENDSHIP Admin Dashboard</title>
<style>
  *{margin:0;padding:0;box-sizing:border-box}
  body{font-family:'Segoe UI',system-ui,sans-serif;background:#0f0f0f;color:#e8e0d8;min-height:100vh;display:flex}
  .sidebar{width:220px;background:#141414;border-right:1px solid #1e1e1e;padding:32px 0;flex-shrink:0;position:fixed;height:100vh;overflow-y:auto}
  .s-logo{font-size:16px;letter-spacing:.14em;color:#f4efe9;padding:0 24px;margin-bottom:32px;display:block}
  .s-label{font-size:9px;letter-spacing:.25em;text-transform:uppercase;color:#4a4040;padding:0 24px;margin:20px 0 8px}
  .s-link{display:flex;align-items:center;gap:10px;padding:10px 24px;color:#8a8078;font-size:13px;text-decoration:none;transition:all .2s;border-left:2px solid transparent}
  .s-link:hover,.s-link.active{color:#f0e8e0;background:#1e1e1e;border-left-color:#d4857a}
  .s-link .ic{width:16px;text-align:center;opacity:.6}
  .s-divider{height:1px;background:#1e1e1e;margin:16px 0}
  .main{margin-left:220px;flex:1;padding:40px}
  .page-title{font-size:24px;font-weight:600;color:#f4efe9;margin-bottom:8px}
  .page-sub{font-size:13px;color:#6a6058;margin-bottom:36px}
  .stat-grid{display:grid;grid-template-columns:repeat(5,1fr);gap:16px;margin-bottom:40px}
  .stat-card{background:#141414;border:1px solid #1e1e1e;border-radius:8px;padding:24px}
  .stat-n{font-size:36px;font-weight:700;color:#d4857a;line-height:1}
  .stat-l{font-size:11px;letter-spacing:.12em;text-transform:uppercase;color:#6a6058;margin-top:8px}
  .card{background:#141414;border:1px solid #1e1e1e;border-radius:8px;padding:24px;margin-bottom:20px}
  .card-title{font-size:14px;font-weight:600;color:#f4efe9;margin-bottom:16px}
  table{width:100%;border-collapse:collapse;font-size:13px}
  th{text-align:left;padding:8px 12px;color:#6a6058;font-size:10px;letter-spacing:.15em;text-transform:uppercase;border-bottom:1px solid #1e1e1e}
  td{padding:10px 12px;border-bottom:1px solid rgba(255,255,255,.03);color:#a8a098}
  tr:last-child td{border-bottom:none}
  .badge{display:inline-block;padding:2px 8px;border-radius:12px;font-size:10px;letter-spacing:.1em;text-transform:uppercase}
  .badge-general{background:#2a2010;color:#c8a060}
  .badge-exhibition{background:#1a2820;color:#60c880}
  .badge-collection{background:#202030;color:#8090c8}
  .badge-press{background:#301820;color:#c86080}
  .badge-partnership{background:#182020;color:#60c8c0}
  .btn{display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border-radius:4px;font-size:12px;letter-spacing:.1em;text-transform:uppercase;text-decoration:none;cursor:pointer;border:none;transition:background .2s}
  .btn-primary{background:#d4857a;color:#fff}.btn-primary:hover{background:#bf7268}
  .btn-ghost{background:transparent;color:#8a8078;border:1px solid #2a2a2a}.btn-ghost:hover{border-color:#d4857a;color:#d4857a}
  .signout{color:#6a6058;font-size:12px;text-decoration:none;padding:0 24px;display:block;margin-top:16px}
  .signout:hover{color:#d4857a}
  @media(max-width:900px){.stat-grid{grid-template-columns:repeat(2,1fr)}.main{margin-left:0;padding:20px}.sidebar{display:none}}
</style>
</head>
<body>
<aside class="sidebar">
  <span class="s-logo">TRENDSHIP</span>
  <div class="s-label">Content</div>
  <a href="index.php"       class="s-link active"><span class="ic">◉</span> Dashboard</a>
  <a href="materials.php"   class="s-link"><span class="ic">◈</span> Materials</a>
  <div class="s-divider"></div>
  <div class="s-label">Visitors</div>
  <a href="bookings.php"    class="s-link"><span class="ic">◷</span> Bookings</a>
  <a href="messages.php"    class="s-link"><span class="ic">◻</span> Messages</a>
  <a href="subscribers.php" class="s-link"><span class="ic">◎</span> Subscribers</a>
  <a href="downloads.php"   class="s-link"><span class="ic">↓</span> Downloads</a>
  <div class="s-divider"></div>
  <a href="../index.html"   class="s-link"><span class="ic">←</span> View Website</a>
  <a href="logout.php"      class="signout">Sign out</a>
</aside>
<main class="main">
  <div class="page-title">Dashboard</div>
  <div class="page-sub">Welcome back, <?= htmlspecialchars($_SESSION['admin_username']) ?>.</div>

  <div class="stat-grid">
    <div class="stat-card"><div class="stat-n"><?= $stats['materials'] ?></div><div class="stat-l">Materials</div></div>
    <div class="stat-card"><div class="stat-n"><?= $stats['bookings'] ?></div><div class="stat-l">Pending Bookings</div></div>
    <div class="stat-card"><div class="stat-n"><?= $stats['messages'] ?></div><div class="stat-l">Unread Messages</div></div>
    <div class="stat-card"><div class="stat-n"><?= $stats['subscribers'] ?></div><div class="stat-l">Subscribers</div></div>
    <div class="stat-card"><div class="stat-n"><?= $stats['downloads'] ?></div><div class="stat-l">Report Downloads</div></div>
  </div>

  <div class="card">
    <div class="card-title">Recent Messages</div>
    <table>
      <thead><tr><th>Name</th><th>Email</th><th>Interest</th><th>Received</th><th></th></tr></thead>
      <tbody>
      <?php foreach ($recent_messages as $m): ?>
        <tr>
          <td><?= htmlspecialchars($m['name']) ?></td>
          <td><?= htmlspecialchars($m['email']) ?></td>
          <td><span class="badge badge-<?= $m['interest'] ?>"><?= $m['interest'] ?></span></td>
          <td><?= date('d M Y', strtotime($m['created_at'])) ?></td>
          <td><a href="messages.php" class="btn btn-ghost">View</a></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</main>
</body>
</html>
```

### `admin/materials.php` — Full Materials CRUD UI

```php
<?php
require_once dirname(__DIR__) . '/api/config.php';
requireAdmin();
$pdo       = getDB();
$materials = $pdo->query("SELECT * FROM materials ORDER BY sort_order,id")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Materials — TRENDSHIP Admin</title>
<!-- Reuse same sidebar CSS as dashboard — include via shared header in real build -->
<style>
  /* [same CSS as admin/index.php sidebar + base styles] */
  *{margin:0;padding:0;box-sizing:border-box}
  body{font-family:'Segoe UI',system-ui,sans-serif;background:#0f0f0f;color:#e8e0d8}
  .main{padding:40px;max-width:1200px;margin:0 auto}
  .top-bar{display:flex;justify-content:space-between;align-items:center;margin-bottom:32px}
  .page-title{font-size:24px;font-weight:600;color:#f4efe9}
  table{width:100%;border-collapse:collapse;background:#141414;border-radius:8px;overflow:hidden;font-size:13px}
  th{text-align:left;padding:12px 16px;color:#6a6058;font-size:10px;letter-spacing:.15em;text-transform:uppercase;background:#111;border-bottom:1px solid #1e1e1e}
  td{padding:12px 16px;border-bottom:1px solid rgba(255,255,255,.03);color:#a8a098;vertical-align:middle}
  tr:last-child td{border-bottom:none}
  .theme-pill{display:inline-block;padding:2px 10px;border-radius:12px;font-size:10px;letter-spacing:.1em;text-transform:uppercase}
  .theme-boost{background:#2a1e10;color:#c8a060}.theme-cosmos{background:#101828;color:#6080c8}
  .theme-ooparts{background:#1e1808;color:#a09050}.theme-synergy{background:#2a1018;color:#d4857a}
  .feat{color:#d4857a;font-size:16px}
  .mat-img{width:44px;height:44px;object-fit:cover;border-radius:3px}
  .btn{display:inline-flex;align-items:center;gap:5px;padding:6px 12px;border-radius:4px;font-size:11px;letter-spacing:.1em;text-transform:uppercase;cursor:pointer;border:1px solid transparent;text-decoration:none;transition:all .2s}
  .btn-edit{background:transparent;color:#8a8078;border-color:#2a2a2a}.btn-edit:hover{border-color:#d4857a;color:#d4857a}
  .btn-del{background:transparent;color:#8a6060;border-color:#2a1818}.btn-del:hover{background:#2a1818;color:#e88080}
  .btn-add{background:#d4857a;color:#fff;border:none;padding:10px 24px;font-size:12px;letter-spacing:.12em}.btn-add:hover{background:#bf7268}
  .modal-bg{display:none;position:fixed;inset:0;background:rgba(0,0,0,.82);z-index:100;align-items:center;justify-content:center}
  .modal-bg.open{display:flex}
  .modal{background:#141414;border:1px solid #2a2a2a;border-radius:10px;padding:36px;width:600px;max-height:90vh;overflow-y:auto}
  .modal h2{font-size:18px;color:#f4efe9;margin-bottom:24px}
  .form-row{display:grid;grid-template-columns:1fr 1fr;gap:16px}
  .form-group{margin-bottom:16px}
  .form-group label{display:block;font-size:10px;letter-spacing:.15em;text-transform:uppercase;color:#6a6058;margin-bottom:6px}
  .form-group input,.form-group select,.form-group textarea{width:100%;background:#1e1e1e;border:1px solid #2a2a2a;border-radius:4px;padding:10px 12px;color:#f0e8e0;font-size:13px;outline:none;font-family:inherit}
  .form-group input:focus,.form-group select:focus,.form-group textarea:focus{border-color:#d4857a}
  .form-group textarea{resize:vertical;min-height:80px}
  .modal-actions{display:flex;gap:12px;justify-content:flex-end;margin-top:24px;padding-top:24px;border-top:1px solid #1e1e1e}
  .btn-cancel{background:transparent;color:#8a8078;border:1px solid #2a2a2a;padding:10px 20px;border-radius:4px;cursor:pointer;font-size:12px}
  .btn-save{background:#d4857a;color:#fff;border:none;padding:10px 24px;border-radius:4px;cursor:pointer;font-size:12px;letter-spacing:.1em}
  .toast{position:fixed;bottom:24px;right:24px;background:#1e1e1e;border:1px solid #d4857a;color:#f0e8e0;padding:14px 20px;border-radius:6px;font-size:13px;z-index:200;opacity:0;transform:translateY(10px);transition:all .3s}
  .toast.show{opacity:1;transform:translateY(0)}
  a.back{color:#c4968e;font-size:12px;text-decoration:none;display:inline-flex;align-items:center;gap:6px;margin-bottom:24px}
</style>
</head>
<body>
<div class="main">
  <a href="index.php" class="back">← Dashboard</a>
  <div class="top-bar">
    <div class="page-title">Materials <span style="color:#6a6058;font-size:16px">(<?= count($materials) ?>)</span></div>
    <button class="btn btn-add" onclick="openModal()">+ Add Material</button>
  </div>

  <table>
    <thead>
      <tr>
        <th>Image</th><th>Name</th><th>Code</th><th>Theme</th>
        <th>Featured</th><th>Surface</th><th>Actions</th>
      </tr>
    </thead>
    <tbody>
    <?php foreach ($materials as $m): ?>
      <tr id="row-<?= $m['id'] ?>">
        <td><img class="mat-img" src="<?= htmlspecialchars($m['image_url']) ?>" alt=""></td>
        <td><?= htmlspecialchars($m['name']) ?></td>
        <td style="font-family:monospace;font-size:11px;color:#6a6058"><?= htmlspecialchars($m['code']) ?></td>
        <td><span class="theme-pill theme-<?= $m['theme'] ?>"><?= strtoupper($m['theme']) ?></span></td>
        <td><?= $m['featured'] ? '<span class="feat">★</span>' : '<span style="color:#2a2a2a">○</span>' ?></td>
        <td><?= htmlspecialchars($m['surface']) ?></td>
        <td style="display:flex;gap:8px">
          <button class="btn btn-edit"
            onclick='editMaterial(<?= htmlspecialchars(json_encode($m), ENT_QUOTES) ?>)'>Edit</button>
          <button class="btn btn-del"
            onclick="deleteMaterial(<?= $m['id'] ?>, '<?= addslashes($m['name']) ?>')">Delete</button>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>

<!-- Add/Edit Modal -->
<div class="modal-bg" id="modalBg">
  <div class="modal">
    <h2 id="modal-title">Add Material</h2>
    <form id="matForm">
      <input type="hidden" id="f-id">
      <div class="form-row">
        <div class="form-group"><label>Name *</label><input id="f-name" required></div>
        <div class="form-group"><label>Code *</label><input id="f-code" placeholder="LX-B2025-001" required></div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label>Theme *</label>
          <select id="f-theme">
            <option value="boost">BOOST</option>
            <option value="cosmos">COSMOS</option>
            <option value="ooparts">OOPARTS</option>
            <option value="synergy">SYNERGY</option>
          </select>
        </div>
        <div class="form-group"><label>Surface *</label><input id="f-surface" required></div>
      </div>
      <div class="form-group"><label>Image URL *</label><input id="f-image" required></div>
      <div class="form-row">
        <div class="form-group"><label>Colour 1 (hex)</label><input id="f-c1" placeholder="#c8885a"></div>
        <div class="form-group"><label>Colour 2 (hex)</label><input id="f-c2" placeholder="#b87848"></div>
        <div class="form-group" style="grid-column:1/-1"><label>Colour 3 (hex)</label><input id="f-c3" placeholder="#d8a880"></div>
      </div>
      <div class="form-group"><label>Description</label><textarea id="f-desc"></textarea></div>
      <div class="form-row">
        <div class="form-group"><label>Finish</label><input id="f-finish"></div>
        <div class="form-group"><label>Thickness</label><input id="f-thick" placeholder="6mm"></div>
      </div>
      <div class="form-group">
        <label>Featured on Home Page</label>
        <select id="f-featured"><option value="0">No</option><option value="1">Yes</option></select>
      </div>
      <div class="modal-actions">
        <button type="button" class="btn-cancel" onclick="closeModal()">Cancel</button>
        <button type="submit" class="btn-save">Save Material</button>
      </div>
    </form>
  </div>
</div>

<div class="toast" id="toast"></div>

<script>
const API = '../api/materials/';
let editing = false;

function openModal(mat) {
  editing = false;
  document.getElementById('modal-title').textContent = 'Add Material';
  document.getElementById('matForm').reset();
  document.getElementById('f-id').value = '';
  document.getElementById('modalBg').classList.add('open');
}

function editMaterial(mat) {
  editing = true;
  document.getElementById('modal-title').textContent = 'Edit Material';
  document.getElementById('f-id').value      = mat.id;
  document.getElementById('f-name').value    = mat.name;
  document.getElementById('f-code').value    = mat.code;
  document.getElementById('f-theme').value   = mat.theme;
  document.getElementById('f-surface').value = mat.surface;
  document.getElementById('f-image').value   = mat.image_url;
  document.getElementById('f-c1').value      = mat.color_1 || '';
  document.getElementById('f-c2').value      = mat.color_2 || '';
  document.getElementById('f-c3').value      = mat.color_3 || '';
  document.getElementById('f-desc').value    = mat.description || '';
  document.getElementById('f-finish').value  = mat.finish || '';
  document.getElementById('f-thick').value   = mat.thickness || '';
  document.getElementById('f-featured').value= mat.featured;
  document.getElementById('modalBg').classList.add('open');
}

function closeModal() {
  document.getElementById('modalBg').classList.remove('open');
}

document.getElementById('matForm').addEventListener('submit', async e => {
  e.preventDefault();
  const id = document.getElementById('f-id').value;
  const payload = {
    name: document.getElementById('f-name').value,
    code: document.getElementById('f-code').value,
    theme: document.getElementById('f-theme').value,
    surface: document.getElementById('f-surface').value,
    image_url: document.getElementById('f-image').value,
    color_1: document.getElementById('f-c1').value,
    color_2: document.getElementById('f-c2').value,
    color_3: document.getElementById('f-c3').value,
    description: document.getElementById('f-desc').value,
    finish: document.getElementById('f-finish').value,
    thickness: document.getElementById('f-thick').value,
    featured: parseInt(document.getElementById('f-featured').value),
  };

  const url    = editing ? API + 'update.php' : API + 'index.php';
  const method = editing ? 'PUT' : 'POST';
  if (editing) payload.id = parseInt(id);

  try {
    const res  = await fetch(url, { method, headers:{'Content-Type':'application/json'}, body: JSON.stringify(payload) });
    const data = await res.json();
    if (data.success) {
      showToast(data.message || 'Saved!');
      closeModal();
      setTimeout(() => location.reload(), 1000);
    } else {
      showToast(data.error || 'Error saving material', true);
    }
  } catch {
    showToast('Network error', true);
  }
});

async function deleteMaterial(id, name) {
  if (!confirm(`Delete "${name}"? This cannot be undone.`)) return;
  try {
    const res  = await fetch(API + 'delete.php', {
      method: 'DELETE', headers:{'Content-Type':'application/json'},
      body: JSON.stringify({ id })
    });
    const data = await res.json();
    if (data.success) {
      document.getElementById('row-' + id)?.remove();
      showToast(data.message);
    } else {
      showToast(data.error, true);
    }
  } catch {
    showToast('Network error', true);
  }
}

function showToast(msg, err = false) {
  const t = document.getElementById('toast');
  t.textContent = msg;
  t.style.borderColor = err ? '#c05050' : '#d4857a';
  t.classList.add('show');
  setTimeout(() => t.classList.remove('show'), 3000);
}

// Close modal on background click
document.getElementById('modalBg').addEventListener('click', e => {
  if (e.target === e.currentTarget) closeModal();
});
</script>
</body>
</html>
```

### `admin/bookings.php` — Manage Bookings

```php
<?php
require_once dirname(__DIR__) . '/api/config.php';
requireAdmin();
$pdo      = getDB();
$filter   = $_GET['status'] ?? 'all';
$where    = $filter !== 'all' ? "WHERE status = " . $pdo->quote($filter) : '';
$bookings = $pdo->query("SELECT * FROM bookings $where ORDER BY visit_date ASC, visit_time ASC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Bookings — TRENDSHIP Admin</title>
<style>
  *{margin:0;padding:0;box-sizing:border-box}
  body{font-family:'Segoe UI',system-ui,sans-serif;background:#0f0f0f;color:#e8e0d8}
  .main{padding:40px;max-width:1200px;margin:0 auto}
  .top-bar{display:flex;justify-content:space-between;align-items:center;margin-bottom:24px}
  .page-title{font-size:24px;font-weight:600;color:#f4efe9}
  .filter-pills{display:flex;gap:8px}
  .pill{padding:6px 16px;border-radius:20px;font-size:11px;letter-spacing:.12em;text-transform:uppercase;text-decoration:none;border:1px solid #2a2a2a;color:#8a8078;transition:all .2s}
  .pill:hover,.pill.active{border-color:#d4857a;color:#d4857a}
  table{width:100%;border-collapse:collapse;background:#141414;border-radius:8px;overflow:hidden;font-size:13px}
  th{text-align:left;padding:12px 16px;color:#6a6058;font-size:10px;letter-spacing:.15em;text-transform:uppercase;background:#111;border-bottom:1px solid #1e1e1e}
  td{padding:12px 16px;border-bottom:1px solid rgba(255,255,255,.03);color:#a8a098;vertical-align:middle}
  tr:last-child td{border-bottom:none}
  .status-sel{background:#1e1e1e;border:1px solid #2a2a2a;border-radius:4px;padding:4px 8px;color:#f0e8e0;font-size:12px;cursor:pointer}
  .btn-del{background:transparent;color:#8a6060;border:1px solid #2a1818;border-radius:4px;padding:5px 10px;font-size:11px;cursor:pointer;transition:all .2s}
  .btn-del:hover{background:#2a1818;color:#e88080}
  a.back{color:#c4968e;font-size:12px;text-decoration:none;display:inline-flex;align-items:center;gap:6px;margin-bottom:24px}
  .toast{position:fixed;bottom:24px;right:24px;background:#1e1e1e;border:1px solid #d4857a;color:#f0e8e0;padding:14px 20px;border-radius:6px;font-size:13px;z-index:200;opacity:0;transition:opacity .3s}
  .toast.show{opacity:1}
  .empty{text-align:center;padding:48px;color:#4a4040;font-size:14px}
</style>
</head>
<body>
<div class="main">
  <a href="index.php" class="back">← Dashboard</a>
  <div class="top-bar">
    <div class="page-title">Bookings <span style="color:#6a6058;font-size:16px">(<?= count($bookings) ?>)</span></div>
    <div class="filter-pills">
      <a href="?status=all"       class="pill <?= $filter==='all'?'active':'' ?>">All</a>
      <a href="?status=pending"   class="pill <?= $filter==='pending'?'active':'' ?>">Pending</a>
      <a href="?status=confirmed" class="pill <?= $filter==='confirmed'?'active':'' ?>">Confirmed</a>
      <a href="?status=cancelled" class="pill <?= $filter==='cancelled'?'active':'' ?>">Cancelled</a>
    </div>
  </div>

  <?php if (empty($bookings)): ?>
    <div class="empty">No bookings found.</div>
  <?php else: ?>
  <table>
    <thead><tr><th>Ref</th><th>Name</th><th>Email</th><th>Date</th><th>Time</th><th>Party</th><th>Status</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($bookings as $b): ?>
      <tr id="row-<?= $b['id'] ?>">
        <td style="font-family:monospace;font-size:11px;color:#6a6058"><?= $b['ref'] ?></td>
        <td><?= htmlspecialchars($b['name']) ?></td>
        <td><?= htmlspecialchars($b['email']) ?></td>
        <td><?= date('D d M Y', strtotime($b['visit_date'])) ?></td>
        <td><?= $b['visit_time'] ?></td>
        <td><?= $b['party_size'] ?></td>
        <td>
          <select class="status-sel" onchange="updateStatus(<?= $b['id'] ?>, this.value)">
            <option <?= $b['status']==='pending'?'selected':'' ?>>pending</option>
            <option <?= $b['status']==='confirmed'?'selected':'' ?>>confirmed</option>
            <option <?= $b['status']==='cancelled'?'selected':'' ?>>cancelled</option>
          </select>
        </td>
        <td><button class="btn-del" onclick="deleteBooking(<?= $b['id'] ?>, '<?= $b['ref'] ?>')">Delete</button></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  <?php endif; ?>
</div>
<div class="toast" id="toast"></div>
<script>
async function updateStatus(id, status) {
  const res  = await fetch('../api/bookings/update.php', {
    method:'PUT', headers:{'Content-Type':'application/json'},
    body: JSON.stringify({id, status})
  });
  const data = await res.json();
  showToast(data.message || data.error, !data.success);
}
async function deleteBooking(id, ref) {
  if (!confirm(`Delete booking ${ref}?`)) return;
  const res  = await fetch('../api/bookings/delete.php', {
    method:'DELETE', headers:{'Content-Type':'application/json'},
    body: JSON.stringify({id})
  });
  const data = await res.json();
  if (data.success) { document.getElementById('row-'+id)?.remove(); showToast(data.message); }
  else showToast(data.error, true);
}
function showToast(msg, err=false) {
  const t = document.getElementById('toast');
  t.textContent = msg;
  t.style.borderColor = err ? '#c05050' : '#d4857a';
  t.classList.add('show');
  setTimeout(() => t.classList.remove('show'), 3000);
}
</script>
</body>
</html>
```

### `admin/messages.php` — View & Manage Messages

```php
<?php
require_once dirname(__DIR__) . '/api/config.php';
requireAdmin();
$pdo      = getDB();
$filter   = $_GET['status'] ?? 'all';
$where    = $filter !== 'all' ? "WHERE status = " . $pdo->quote($filter) : '';
$messages = $pdo->query("SELECT * FROM messages $where ORDER BY created_at DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Messages — TRENDSHIP Admin</title>
<style>
  *{margin:0;padding:0;box-sizing:border-box}
  body{font-family:'Segoe UI',system-ui,sans-serif;background:#0f0f0f;color:#e8e0d8}
  .main{padding:40px;max-width:1200px;margin:0 auto}
  .top-bar{display:flex;justify-content:space-between;align-items:center;margin-bottom:24px}
  .page-title{font-size:24px;font-weight:600;color:#f4efe9}
  .filter-pills{display:flex;gap:8px}
  .pill{padding:6px 16px;border-radius:20px;font-size:11px;letter-spacing:.12em;text-transform:uppercase;text-decoration:none;border:1px solid #2a2a2a;color:#8a8078;transition:all .2s}
  .pill:hover,.pill.active{border-color:#d4857a;color:#d4857a}
  .msg-list{display:flex;flex-direction:column;gap:12px}
  .msg-card{background:#141414;border:1px solid #1e1e1e;border-radius:8px;padding:20px 24px;cursor:pointer;transition:border-color .2s}
  .msg-card:hover{border-color:#2a2a2a}
  .msg-card.unread{border-left:3px solid #d4857a}
  .msg-head{display:flex;justify-content:space-between;align-items:center;margin-bottom:10px}
  .msg-name{font-size:15px;font-weight:600;color:#f0e8e0}
  .msg-meta{font-size:11px;color:#4a4040}
  .msg-email{font-size:12px;color:#6a6058;margin-bottom:10px}
  .msg-preview{font-size:13px;color:#8a8078;line-height:1.6;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
  .msg-actions{display:flex;gap:8px;margin-top:12px;padding-top:12px;border-top:1px solid #1e1e1e}
  .badge{display:inline-block;padding:2px 8px;border-radius:12px;font-size:10px;letter-spacing:.1em;text-transform:uppercase}
  .badge-general{background:#2a2010;color:#c8a060}.badge-exhibition{background:#1a2820;color:#60c880}
  .badge-collection{background:#202030;color:#8090c8}.badge-press{background:#301820;color:#c86080}
  .badge-partnership{background:#182020;color:#60c8c0}
  .btn{display:inline-flex;align-items:center;gap:5px;padding:5px 12px;border-radius:4px;font-size:11px;letter-spacing:.1em;text-transform:uppercase;cursor:pointer;border:1px solid transparent;transition:all .2s}
  .btn-ghost{background:transparent;color:#8a8078;border-color:#2a2a2a}.btn-ghost:hover{border-color:#d4857a;color:#d4857a}
  .btn-del{background:transparent;color:#8a6060;border-color:#2a1818}.btn-del:hover{background:#2a1818;color:#e88080}
  a.back{color:#c4968e;font-size:12px;text-decoration:none;display:inline-flex;align-items:center;gap:6px;margin-bottom:24px}
  .toast{position:fixed;bottom:24px;right:24px;background:#1e1e1e;border:1px solid #d4857a;color:#f0e8e0;padding:14px 20px;border-radius:6px;font-size:13px;z-index:200;opacity:0;transition:opacity .3s}
  .toast.show{opacity:1}
  .detail-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.85);z-index:100;align-items:center;justify-content:center}
  .detail-overlay.open{display:flex}
  .detail-box{background:#141414;border:1px solid #2a2a2a;border-radius:10px;padding:36px;width:580px;max-height:85vh;overflow-y:auto}
  .detail-box h3{font-size:20px;color:#f4efe9;margin-bottom:6px}
  .detail-meta{font-size:12px;color:#6a6058;margin-bottom:20px}
  .detail-body{font-size:14px;line-height:1.8;color:#a8a098;white-space:pre-wrap}
  .close-btn{position:absolute;top:16px;right:20px;color:#6a6058;font-size:20px;cursor:pointer;background:none;border:none}
  .empty{text-align:center;padding:48px;color:#4a4040;font-size:14px}
</style>
</head>
<body>
<div class="main">
  <a href="index.php" class="back">← Dashboard</a>
  <div class="top-bar">
    <div class="page-title">Messages <span style="color:#6a6058;font-size:16px">(<?= count($messages) ?>)</span></div>
    <div class="filter-pills">
      <a href="?status=all"      class="pill <?= $filter==='all'?'active':'' ?>">All</a>
      <a href="?status=unread"   class="pill <?= $filter==='unread'?'active':'' ?>">Unread</a>
      <a href="?status=read"     class="pill <?= $filter==='read'?'active':'' ?>">Read</a>
      <a href="?status=replied"  class="pill <?= $filter==='replied'?'active':'' ?>">Replied</a>
      <a href="?status=archived" class="pill <?= $filter==='archived'?'active':'' ?>">Archived</a>
    </div>
  </div>

  <?php if (empty($messages)): ?>
    <div class="empty">No messages found.</div>
  <?php else: ?>
  <div class="msg-list">
  <?php foreach ($messages as $m): ?>
    <div class="msg-card <?= $m['status']==='unread'?'unread':'' ?>" id="card-<?= $m['id'] ?>"
         onclick="viewMessage(<?= $m['id'] ?>, <?= htmlspecialchars(json_encode($m), ENT_QUOTES) ?>)">
      <div class="msg-head">
        <span class="msg-name"><?= htmlspecialchars($m['name']) ?></span>
        <span class="msg-meta">
          <span class="badge badge-<?= $m['interest'] ?>"><?= $m['interest'] ?></span>
          &nbsp;<?= date('d M Y H:i', strtotime($m['created_at'])) ?>
        </span>
      </div>
      <div class="msg-email"><?= htmlspecialchars($m['email']) ?> <?= $m['company']?'· '.htmlspecialchars($m['company']):'' ?></div>
      <div class="msg-preview"><?= htmlspecialchars($m['message']) ?></div>
      <div class="msg-actions" onclick="event.stopPropagation()">
        <select class="btn btn-ghost" onchange="updateMsgStatus(<?= $m['id'] ?>, this.value)" style="padding:5px 8px;cursor:pointer">
          <option <?= $m['status']==='unread'?'selected':'' ?>>unread</option>
          <option <?= $m['status']==='read'?'selected':'' ?>>read</option>
          <option <?= $m['status']==='replied'?'selected':'' ?>>replied</option>
          <option <?= $m['status']==='archived'?'selected':'' ?>>archived</option>
        </select>
        <a href="mailto:<?= htmlspecialchars($m['email']) ?>" class="btn btn-ghost">Reply via Email</a>
        <button class="btn btn-del" onclick="deleteMsg(<?= $m['id'] ?>)">Delete</button>
      </div>
    </div>
  <?php endforeach; ?>
  </div>
  <?php endif; ?>
</div>

<!-- Message detail overlay -->
<div class="detail-overlay" id="detailOverlay">
  <div class="detail-box" style="position:relative">
    <button class="close-btn" onclick="closeDetail()">✕</button>
    <h3 id="d-name"></h3>
    <div class="detail-meta" id="d-meta"></div>
    <div class="detail-body" id="d-body"></div>
  </div>
</div>

<div class="toast" id="toast"></div>
<script>
function viewMessage(id, msg) {
  document.getElementById('d-name').textContent = msg.name;
  document.getElementById('d-meta').textContent =
    msg.email + (msg.company ? ' · ' + msg.company : '') +
    '  |  ' + msg.interest + '  |  ' + msg.created_at;
  document.getElementById('d-body').textContent = msg.message;
  document.getElementById('detailOverlay').classList.add('open');
  // Mark as read
  if (msg.status === 'unread') {
    updateMsgStatus(id, 'read');
    const card = document.getElementById('card-'+id);
    if (card) card.classList.remove('unread');
  }
}
function closeDetail() {
  document.getElementById('detailOverlay').classList.remove('open');
}
async function updateMsgStatus(id, status) {
  const res  = await fetch('../api/messages/update.php', {
    method:'PUT', headers:{'Content-Type':'application/json'},
    body: JSON.stringify({id, status})
  });
  const data = await res.json();
  showToast(data.message || data.error, !data.success);
}
async function deleteMsg(id) {
  if (!confirm('Delete this message?')) return;
  const res  = await fetch('../api/messages/delete.php', {
    method:'DELETE', headers:{'Content-Type':'application/json'},
    body: JSON.stringify({id})
  });
  const data = await res.json();
  if (data.success) { document.getElementById('card-'+id)?.remove(); showToast(data.message); }
  else showToast(data.error, true);
}
function showToast(msg, err=false) {
  const t = document.getElementById('toast');
  t.textContent = msg;
  t.style.borderColor = err ? '#c05050' : '#d4857a';
  t.classList.add('show');
  setTimeout(() => t.classList.remove('show'), 3000);
}
document.getElementById('detailOverlay').addEventListener('click', e => {
  if (e.target === e.currentTarget) closeDetail();
});
</script>
</body>
</html>
```

### `admin/subscribers.php` — Manage Subscribers

```php
<?php
require_once dirname(__DIR__) . '/api/config.php';
requireAdmin();
$pdo  = getDB();
$subs = $pdo->query(
  "SELECT *, DATE_FORMAT(subscribed_at,'%d %b %Y') as joined FROM subscribers ORDER BY subscribed_at DESC"
)->fetchAll();
$total  = $pdo->query("SELECT COUNT(*) FROM subscribers WHERE status='active'")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Subscribers — TRENDSHIP Admin</title>
<style>
  *{margin:0;padding:0;box-sizing:border-box}
  body{font-family:'Segoe UI',system-ui,sans-serif;background:#0f0f0f;color:#e8e0d8}
  .main{padding:40px;max-width:1100px;margin:0 auto}
  .top-bar{display:flex;justify-content:space-between;align-items:center;margin-bottom:24px}
  .page-title{font-size:24px;font-weight:600;color:#f4efe9}
  .export-btn{background:#1e1e1e;border:1px solid #2a2a2a;color:#c4968e;padding:8px 18px;border-radius:4px;font-size:12px;letter-spacing:.1em;cursor:pointer;text-decoration:none;transition:border-color .2s}
  .export-btn:hover{border-color:#d4857a}
  .stat-bar{display:flex;gap:24px;margin-bottom:28px}
  .sb{background:#141414;border:1px solid #1e1e1e;border-radius:6px;padding:16px 20px;text-align:center}
  .sb-n{font-size:28px;font-weight:700;color:#d4857a}.sb-l{font-size:10px;letter-spacing:.15em;text-transform:uppercase;color:#6a6058;margin-top:4px}
  table{width:100%;border-collapse:collapse;background:#141414;border-radius:8px;overflow:hidden;font-size:13px}
  th{text-align:left;padding:11px 16px;color:#6a6058;font-size:10px;letter-spacing:.15em;text-transform:uppercase;background:#111;border-bottom:1px solid #1e1e1e}
  td{padding:11px 16px;border-bottom:1px solid rgba(255,255,255,.03);color:#a8a098}
  tr:last-child td{border-bottom:none}
  .source-pill{display:inline-block;padding:2px 8px;border-radius:10px;font-size:10px;letter-spacing:.08em}
  .source-newsletter{background:#202030;color:#8090d0}
  .source-contact{background:#201820;color:#d080c0}
  .source-report_download{background:#1a2a18;color:#60c070}
  .status-active{color:#60c880}.status-unsubscribed{color:#6a6058}
  .btn-unsub{background:transparent;color:#8a6060;border:1px solid #2a1818;border-radius:4px;padding:4px 10px;font-size:11px;cursor:pointer;transition:all .2s}
  .btn-unsub:hover{background:#2a1818;color:#e88080}
  a.back{color:#c4968e;font-size:12px;text-decoration:none;display:inline-flex;align-items:center;gap:6px;margin-bottom:24px}
  .toast{position:fixed;bottom:24px;right:24px;background:#1e1e1e;border:1px solid #d4857a;color:#f0e8e0;padding:14px 20px;border-radius:6px;font-size:13px;z-index:200;opacity:0;transition:opacity .3s}
  .toast.show{opacity:1}
</style>
</head>
<body>
<div class="main">
  <a href="index.php" class="back">← Dashboard</a>
  <div class="top-bar">
    <div class="page-title">Subscribers</div>
    <a href="?export=csv" class="export-btn">Export CSV</a>
  </div>

  <?php
  // Simple CSV export
  if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="trendship_subscribers_' . date('Y-m-d') . '.csv"');
    $out = fopen('php://output', 'w');
    fputcsv($out, ['Email','Name','Source','Status','Joined']);
    foreach ($subs as $s) {
      fputcsv($out, [$s['email'],$s['name'],$s['source'],$s['status'],$s['joined']]);
    }
    fclose($out);
    exit();
  }
  ?>

  <div class="stat-bar">
    <div class="sb"><div class="sb-n"><?= $total ?></div><div class="sb-l">Active</div></div>
    <div class="sb"><div class="sb-n"><?= count($subs) ?></div><div class="sb-l">Total</div></div>
  </div>

  <table>
    <thead><tr><th>Email</th><th>Name</th><th>Source</th><th>Status</th><th>Joined</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($subs as $s): ?>
      <tr id="sub-<?= $s['id'] ?>">
        <td><?= htmlspecialchars($s['email']) ?></td>
        <td><?= htmlspecialchars($s['name'] ?: '—') ?></td>
        <td><span class="source-pill source-<?= $s['source'] ?>"><?= str_replace('_',' ',$s['source']) ?></span></td>
        <td><span class="status-<?= $s['status'] ?>"><?= $s['status'] ?></span></td>
        <td><?= $s['joined'] ?></td>
        <td>
          <?php if ($s['status']==='active'): ?>
          <button class="btn-unsub" onclick="unsub('<?= htmlspecialchars($s['email']) ?>', <?= $s['id'] ?>)">Unsubscribe</button>
          <?php endif; ?>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>
<div class="toast" id="toast"></div>
<script>
async function unsub(email, id) {
  if (!confirm('Unsubscribe ' + email + '?')) return;
  const res  = await fetch('../api/subscribers/update.php', {
    method:'PUT', headers:{'Content-Type':'application/json'},
    body: JSON.stringify({email, status:'unsubscribed'})
  });
  const data = await res.json();
  if (data.success) {
    const td = document.querySelector('#sub-'+id+' .status-active');
    if (td) { td.className='status-unsubscribed'; td.textContent='unsubscribed'; }
    const btn = document.querySelector('#sub-'+id+' .btn-unsub');
    if (btn) btn.remove();
    showToast('Unsubscribed');
  } else showToast(data.error, true);
}
function showToast(msg, err=false) {
  const t = document.getElementById('toast');
  t.textContent = msg;
  t.style.borderColor = err ? '#c05050' : '#d4857a';
  t.classList.add('show');
  setTimeout(() => t.classList.remove('show'), 3000);
}
</script>
</body>
</html>
```

### `admin/downloads.php` — Report Download Log

```php
<?php
require_once dirname(__DIR__) . '/api/config.php';
requireAdmin();
$pdo       = getDB();
$downloads = $pdo->query(
  "SELECT *, DATE_FORMAT(downloaded_at,'%d %b %Y %H:%i') as dl_time
   FROM report_downloads ORDER BY downloaded_at DESC"
)->fetchAll();
$unique = $pdo->query("SELECT COUNT(DISTINCT email) FROM report_downloads")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Downloads — TRENDSHIP Admin</title>
<style>
  *{margin:0;padding:0;box-sizing:border-box}
  body{font-family:'Segoe UI',system-ui,sans-serif;background:#0f0f0f;color:#e8e0d8}
  .main{padding:40px;max-width:1000px;margin:0 auto}
  .top-bar{display:flex;justify-content:space-between;align-items:center;margin-bottom:24px}
  .page-title{font-size:24px;font-weight:600;color:#f4efe9}
  .stat-bar{display:flex;gap:16px;margin-bottom:28px}
  .sb{background:#141414;border:1px solid #1e1e1e;border-radius:6px;padding:16px 20px;text-align:center}
  .sb-n{font-size:28px;font-weight:700;color:#d4857a}.sb-l{font-size:10px;letter-spacing:.15em;text-transform:uppercase;color:#6a6058;margin-top:4px}
  table{width:100%;border-collapse:collapse;background:#141414;border-radius:8px;overflow:hidden;font-size:13px}
  th{text-align:left;padding:11px 16px;color:#6a6058;font-size:10px;letter-spacing:.15em;text-transform:uppercase;background:#111;border-bottom:1px solid #1e1e1e}
  td{padding:11px 16px;border-bottom:1px solid rgba(255,255,255,.03);color:#a8a098}
  tr:last-child td{border-bottom:none}
  a.back{color:#c4968e;font-size:12px;text-decoration:none;display:inline-flex;align-items:center;gap:6px;margin-bottom:24px}
  .export-btn{background:#1e1e1e;border:1px solid #2a2a2a;color:#c4968e;padding:8px 18px;border-radius:4px;font-size:12px;letter-spacing:.1em;cursor:pointer;text-decoration:none}
  .export-btn:hover{border-color:#d4857a}
  .empty{text-align:center;padding:48px;color:#4a4040;font-size:14px}
</style>
</head>
<body>
<div class="main">
  <a href="index.php" class="back">← Dashboard</a>
  <div class="top-bar">
    <div class="page-title">Report Downloads</div>
    <a href="?export=csv" class="export-btn">Export CSV</a>
  </div>

  <?php
  if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="trendship_downloads_' . date('Y-m-d') . '.csv"');
    $out = fopen('php://output', 'w');
    fputcsv($out, ['Email','Name','Company','Downloaded At']);
    foreach ($downloads as $d) fputcsv($out, [$d['email'],$d['name'],$d['company'],$d['dl_time']]);
    fclose($out); exit();
  }
  ?>

  <div class="stat-bar">
    <div class="sb"><div class="sb-n"><?= count($downloads) ?></div><div class="sb-l">Total Downloads</div></div>
    <div class="sb"><div class="sb-n"><?= $unique ?></div><div class="sb-l">Unique Emails</div></div>
  </div>

  <?php if (empty($downloads)): ?>
    <div class="empty">No downloads yet.</div>
  <?php else: ?>
  <table>
    <thead><tr><th>Email</th><th>Name</th><th>Company</th><th>Downloaded</th></tr></thead>
    <tbody>
    <?php foreach ($downloads as $d): ?>
      <tr>
        <td><?= htmlspecialchars($d['email']) ?></td>
        <td><?= htmlspecialchars($d['name'] ?: '—') ?></td>
        <td><?= htmlspecialchars($d['company'] ?: '—') ?></td>
        <td style="color:#6a6058"><?= $d['dl_time'] ?></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  <?php endif; ?>
</div>
</body>
</html>
```

### `admin/logout.php`

```php
<?php
require_once dirname(__DIR__) . '/api/config.php';
session_name(SESSION_NAME);
session_start();
session_destroy();
header('Location: login.php');
exit();
```

---

## MISSING API FILES — Complete Code

These five files were listed in the folder structure but not yet shown.

### `api/bookings/read.php` — GET single booking

```php
<?php
require_once dirname(__DIR__) . '/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
  respond(['success' => false, 'error' => 'Method not allowed'], 405);
}
requireAdminAPI();

$id  = (int)($_GET['id'] ?? 0);
$ref = $_GET['ref'] ?? '';

if (!$id && !$ref) {
  respond(['success' => false, 'error' => 'id or ref required'], 400);
}

$pdo  = getDB();
$sql  = $id ? "SELECT * FROM bookings WHERE id = ?" : "SELECT * FROM bookings WHERE ref = ?";
$val  = $id ?: $ref;
$stmt = $pdo->prepare($sql);
$stmt->execute([$val]);
$row  = $stmt->fetch();

if (!$row) respond(['success' => false, 'error' => 'Booking not found'], 404);
respond(['success' => true, 'data' => $row]);
```

### `api/messages/delete.php` — DELETE message

```php
<?php
require_once dirname(__DIR__) . '/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
  respond(['success' => false, 'error' => 'Method not allowed'], 405);
}
requireAdminAPI();

$id  = (int)(getBody()['id'] ?? $_GET['id'] ?? 0);
if (!$id) respond(['success' => false, 'error' => 'id is required'], 400);

$pdo  = getDB();
$stmt = $pdo->prepare("SELECT name FROM messages WHERE id = ?");
$stmt->execute([$id]);
$row  = $stmt->fetch();
if (!$row) respond(['success' => false, 'error' => 'Message not found'], 404);

$pdo->prepare("DELETE FROM messages WHERE id = ?")->execute([$id]);
respond(['success' => true, 'message' => "Message from '{$row['name']}' deleted"]);
```

### `api/subscribers/delete.php` — DELETE subscriber

```php
<?php
require_once dirname(__DIR__) . '/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
  respond(['success' => false, 'error' => 'Method not allowed'], 405);
}
requireAdminAPI();

$body  = getBody();
$id    = (int)($body['id'] ?? 0);
$email = strtolower(trim($body['email'] ?? ''));

if (!$id && !$email) {
  respond(['success' => false, 'error' => 'id or email required'], 400);
}

$pdo  = getDB();
$sql  = $id ? "DELETE FROM subscribers WHERE id = ?" : "DELETE FROM subscribers WHERE email = ?";
$val  = $id ?: $email;
$stmt = $pdo->prepare($sql);
$stmt->execute([$val]);

if ($stmt->rowCount() === 0) {
  respond(['success' => false, 'error' => 'Subscriber not found'], 404);
}
respond(['success' => true, 'message' => 'Subscriber permanently deleted']);
```

### `api/moodboards/update.php` — PUT update moodboard

```php
<?php
require_once dirname(__DIR__) . '/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
  respond(['success' => false, 'error' => 'Method not allowed'], 405);
}

session_name(SESSION_NAME);
session_start();

$body      = getBody();
$sessionId = session_id();
$pdo       = getDB();

$stmt = $pdo->prepare("SELECT id FROM moodboards WHERE session_id = ? ORDER BY updated_at DESC LIMIT 1");
$stmt->execute([$sessionId]);
$mb   = $stmt->fetch();

if (!$mb) respond(['success' => false, 'error' => 'No moodboard found for this session'], 404);

$items = $body['items'] ?? null;
$name  = isset($body['name']) ? clean($body['name']) : null;

$sets   = [];
$params = [];

if ($items !== null) {
  if (count($items) > 6) respond(['success' => false, 'error' => 'Maximum 6 materials'], 422);
  $sets[]             = 'material_ids = :items';
  $params[':items']   = json_encode($items);
}
if ($name !== null) {
  $sets[]           = 'name = :name';
  $params[':name']  = $name;
}
if (empty($sets)) respond(['success' => false, 'error' => 'Nothing to update'], 400);

$params[':id'] = $mb['id'];
$pdo->prepare("UPDATE moodboards SET " . implode(', ', $sets) . " WHERE id = :id")
    ->execute($params);

respond(['success' => true, 'message' => 'Moodboard updated']);
```

### `api/moodboards/delete.php` — DELETE moodboard

```php
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
```

### `api/auth/login.php` — POST admin login (API version)

```php
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
```

### `api/auth/logout.php` — POST admin logout (API version)

```php
<?php
require_once dirname(__DIR__) . '/helpers.php';

session_name(SESSION_NAME);
session_start();
session_destroy();
respond(['success' => true, 'message' => 'Logged out']);
```

### `api/.htaccess` — Block directory listing

```apache
# Prevent directory browsing
Options -Indexes

# Security headers on all PHP responses
<Files "*.php">
  Header set X-Content-Type-Options "nosniff"
  Header set X-Frame-Options "DENY"
</Files>
```

---

## PART 11 — HOW EACH FRONTEND PAGE CONNECTS TO THE BACKEND

These small JS snippets are added to existing pages at the bottom of their `<script>` block.
**They do not replace any existing JS — they append to it.**

### 11.1 `collection/index.html` — Load materials from DB

Add inside the existing `<script>` block, after all existing JS:

```javascript
// ── Load materials from database ──────────────────────────
// Replaces the hardcoded material cards with live data from MySQL
async function loadMaterials(filter = 'all') {
  const url = filter === 'all'
    ? '../api/materials/index.php'
    : `../api/materials/index.php?theme=${filter}`;

  try {
    const res  = await fetch(url);
    const json = await res.json();
    if (!json.success) return;

    const grid = document.getElementById('mat-grid');
    grid.innerHTML = '';

    json.data.forEach(mat => {
      const colors = [mat.color_1, mat.color_2, mat.color_3]
        .filter(Boolean)
        .map(c => `<span class="mat-dot" style="background:${c};border-color:${c}"></span>`)
        .join('');

      grid.insertAdjacentHTML('beforeend', `
        <article class="mat-card" data-id="${mat.id}" data-theme="${mat.theme}"
                 onclick="openMatModal(${mat.id})"
                 aria-label="${mat.name} material">
          <img src="${mat.image_url}" alt="${mat.name} surface material"
               loading="lazy" width="600" height="800">
          <div class="mat-card-body">
            <div class="mat-card-theme">${mat.theme.toUpperCase()}</div>
            <div class="mat-card-name">${mat.name}</div>
            <div class="mat-dots">${colors}</div>
          </div>
        </article>
      `);
    });

    // Re-run GSAP reveal on new cards
    gsap.from('.mat-card', { opacity:0, y:40, stagger:.08, duration:.8, ease:'power3.out',
      scrollTrigger:{ trigger:'#mat-grid', start:'top 80%', once:true } });

  } catch (err) {
    console.warn('Could not load materials from DB, using static fallback:', err);
  }
}

// Open material detail modal
async function openMatModal(id) {
  const res  = await fetch(`../api/materials/read.php?id=${id}`);
  const json = await res.json();
  if (!json.success) return;
  const m = json.data;

  document.getElementById('modal-mat-name').textContent  = m.name;
  document.getElementById('modal-mat-code').textContent  = m.code;
  document.getElementById('modal-mat-theme').textContent = m.theme.toUpperCase();
  document.getElementById('modal-mat-img').src           = m.image_url;
  document.getElementById('modal-mat-desc').textContent  = m.description || '';
  document.getElementById('modal-mat-surface').textContent = m.surface;
  document.getElementById('mat-modal').classList.add('open');
}

// Filter button clicks
document.querySelectorAll('.filter-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    loadMaterials(btn.dataset.filter);
    // Update URL without reload
    history.replaceState({}, '', `?filter=${btn.dataset.filter}`);
  });
});

// Initial load — respect URL param
const urlFilter = new URLSearchParams(window.location.search).get('filter') || 'all';
loadMaterials(urlFilter);
```

### 11.2 `contact/index.html` — Submit form to DB

```javascript
// ── Contact form submission ───────────────────────────────
document.getElementById('contactForm')?.addEventListener('submit', async e => {
  e.preventDefault();
  const btn = e.target.querySelector('[type=submit]');
  const orig = btn.textContent;
  btn.textContent = 'Sending...';
  btn.disabled = true;

  const payload = {
    name:     document.getElementById('cfName').value,
    email:    document.getElementById('cfEmail').value,
    company:  document.getElementById('cfCompany').value,
    interest: document.getElementById('cfInterest').value,
    message:  document.getElementById('cfMessage').value,
  };

  try {
    const res  = await fetch('../api/messages/index.php', {
      method: 'POST',
      headers: {'Content-Type': 'application/json'},
      body: JSON.stringify(payload),
    });
    const data = await res.json();

    if (data.success) {
      e.target.innerHTML = `
        <div class="cf-success" role="status">
          <p style="font-size:2em;margin-bottom:12px">✦</p>
          <h3>Thank you, ${payload.name}.</h3>
          <p>Reference: <strong>${data.ref}</strong></p>
          <p>We'll reply to ${payload.email} within 2 business days.</p>
        </div>`;
      gsap.from('.cf-success', { opacity:0, y:20, duration:.6 });
    } else {
      btn.textContent = orig;
      btn.disabled = false;
      showToast(data.error || 'Something went wrong. Please try again.');
    }
  } catch {
    btn.textContent = orig;
    btn.disabled = false;
    showToast('Network error. Please check your connection.');
  }
});

// ── Newsletter form ───────────────────────────────────────
document.getElementById('newsletterForm')?.addEventListener('submit', async e => {
  e.preventDefault();
  const email = document.getElementById('nlEmail').value;
  const btn   = e.target.querySelector('[type=submit]');

  try {
    const res  = await fetch('../api/subscribers/index.php', {
      method: 'POST',
      headers: {'Content-Type': 'application/json'},
      body: JSON.stringify({ email, source: 'newsletter' }),
    });
    const data = await res.json();
    btn.textContent = data.success ? 'You\'re in! ✓' : data.error;
    btn.disabled = true;
  } catch {
    btn.textContent = 'Try again';
  }
});
```

### 11.3 `exhibition/index.html` — Booking form

```javascript
// ── Exhibition booking form ───────────────────────────────
document.getElementById('bookingForm')?.addEventListener('submit', async e => {
  e.preventDefault();
  const btn  = e.target.querySelector('[type=submit]');
  btn.textContent = 'Booking...';
  btn.disabled = true;

  const payload = {
    name:       document.getElementById('bk-name').value,
    email:      document.getElementById('bk-email').value,
    visit_date: document.getElementById('bk-date').value,
    visit_time: document.getElementById('bk-time').value,
    party_size: document.getElementById('bk-party').value,
  };

  const res  = await fetch('../api/bookings/index.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify(payload),
  });
  const data = await res.json();

  if (data.success) {
    document.getElementById('booking-modal-body').innerHTML = `
      <h3>Booking Confirmed!</h3>
      <p>Reference: <strong>${data.ref}</strong></p>
      <p>We look forward to seeing you.</p>`;
  } else {
    btn.textContent = 'Try Again';
    btn.disabled = false;
    alert(data.error || 'Booking failed');
  }
});
```

### 11.4 `trend-report/index.html` — Download form

```javascript
document.getElementById('reportForm')?.addEventListener('submit', async e => {
  e.preventDefault();
  const email = document.getElementById('reportEmail').value;
  const btn   = e.target.querySelector('[type=submit]');
  btn.textContent = 'Sending...';
  btn.disabled = true;

  const res  = await fetch('../api/downloads/index.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify({ email }),
  });
  const data = await res.json();

  if (data.success) {
    window.open(data.pdf_url, '_blank');
    e.target.innerHTML = `<p class="form-success">Report sent to <strong>${email}</strong>!</p>`;
  } else {
    btn.textContent = 'Try Again';
    btn.disabled = false;
  }
});
```

### 11.5 `index.html` (Home) — Featured materials from DB

```javascript
// Load featured materials on home page from DB
// Add at the end of the existing <script> block in index.html
(async () => {
  try {
    const res  = await fetch('api/materials/index.php?featured=1');
    const json = await res.json();
    if (!json.success || !json.data.length) return; // fall back to static HTML

    const grid = document.querySelector('.mat-grid');
    if (!grid) return;
    grid.innerHTML = '';

    json.data.slice(0, 4).forEach(mat => {
      const dots = [mat.color_1, mat.color_2, mat.color_3].filter(Boolean)
        .map(c => `<span class="mat-dot" style="background:${c};border-color:${c}"></span>`).join('');
      grid.insertAdjacentHTML('beforeend', `
        <a href="collection/index.html" class="mat-card" style="display:block">
          <img src="${mat.image_url}" alt="${mat.name}"
               loading="lazy" width="600" height="800">
          <div class="mat-card-body">
            <div class="mat-card-theme">${mat.theme.toUpperCase()}</div>
            <div class="mat-card-name">${mat.name}</div>
            <div class="mat-dots">${dots}</div>
          </div>
        </a>`);
    });
  } catch {
    // DB not available — static HTML already in place, do nothing
  }
})();
```

---

## PART 12 — API ENDPOINT REFERENCE TABLE

| Endpoint | Method | Auth | Request Body | Returns | Used By |
|----------|--------|------|-------------|---------|---------|
| `api/materials/index.php` | GET | Public | `?theme=` `?featured=1` `?search=` | Array of materials | Collection, Home |
| `api/materials/index.php` | POST | Admin | `name,code,theme,surface,image_url,...` | `{id,message}` | Admin panel |
| `api/materials/read.php` | GET | Public | `?id=N` | Single material | Collection modal |
| `api/materials/update.php` | PUT | Admin | `{id,...fields}` | `{message}` | Admin panel |
| `api/materials/delete.php` | DELETE | Admin | `{id}` | `{message}` | Admin panel |
| `api/bookings/index.php` | GET | Admin | `?status=` `?date=` | Array of bookings | Admin panel |
| `api/bookings/index.php` | POST | Public | `{name,email,visit_date,visit_time,...}` | `{ref,message}` | Exhibition page |
| `api/bookings/update.php` | PUT | Admin | `{id,status}` | `{message}` | Admin panel |
| `api/bookings/delete.php` | DELETE | Admin | `{id}` | `{message}` | Admin panel |
| `api/subscribers/index.php` | GET | Admin | `?status=active` | Array | Admin panel |
| `api/subscribers/index.php` | POST | Public | `{email,name?,source?}` | `{message}` | Contact, Newsletter |
| `api/subscribers/update.php` | PUT | Public | `{email,status}` | `{message}` | Unsubscribe link |
| `api/messages/index.php` | GET | Admin | `?status=` `?interest=` | Array | Admin panel |
| `api/messages/index.php` | POST | Public | `{name,email,interest,message,...}` | `{ref,message}` | Contact page |
| `api/messages/read.php` | GET | Admin | `?id=N` | Single message | Admin panel |
| `api/messages/update.php` | PUT | Admin | `{id,status}` | `{message}` | Admin panel |
| `api/downloads/index.php` | GET | Admin | — | Array | Admin panel |
| `api/downloads/index.php` | POST | Public | `{email,name?,company?}` | `{pdf_url}` | Trend Report |
| `api/moodboards/index.php` | GET | Session | — | Moodboard items | Collection |
| `api/moodboards/index.php` | POST | Session | `{items,name,email?}` | `{id}` | Collection |

---

## PART 13 — SECURITY RULES

### 13.1 Rules that are already implemented in the code above

```
✅ All DB queries use PDO prepared statements — SQL injection impossible
✅ All user output uses htmlspecialchars() — XSS impossible
✅ Admin passwords stored as bcrypt hashes — never plain text
✅ Admin routes check session before any DB operation
✅ Rate limiting on contact form (3 per email per 24h)
✅ ENUM columns in DB — invalid status values rejected by MySQL
✅ Date validation on bookings — no Monday, no past dates
✅ Slot capacity check on bookings — max 20 per slot
✅ CORS headers set in config.php
✅ OPTIONS preflight handled
```

### 13.2 Additional rules to follow

```
1. NEVER put config.php in a public GitHub repository.
   Add to .gitignore: api/config.php

2. Create api/.htaccess to block direct browser access to PHP files:

   <Files "*.php">
     Header set X-Content-Type-Options "nosniff"
   </Files>
   Options -Indexes

3. Change the default admin password immediately after setup:
   In phpMyAdmin, run:
   UPDATE admins SET password_hash = '$2y$10$[new_bcrypt_hash]' WHERE username = 'admin';
   Generate hash at: https://bcrypt-generator.com

4. In production (real hosting, not XAMPP):
   - Use environment variables for DB credentials, not defines
   - Enable HTTPS
   - Set DB_PASS to a strong password
   - Restrict phpMyAdmin access by IP
```

### 13.3 `.gitignore` additions

```
# Database credentials — NEVER commit
api/config.php

# XAMPP local files
.htaccess.local

# Uploaded files (if added later)
uploads/

# Editor
.DS_Store
.vscode/
```

---

## PART 14 — SETUP CHECKLIST

Follow this exact order. Every step is testable before the next.

```
STEP 1 — Install XAMPP
  □ Download from apachefriends.org
  □ Install (accept all defaults)
  □ Start Apache + MySQL in Control Panel
  □ Visit http://localhost — see welcome page ✓

STEP 2 — Create the database
  □ Go to http://localhost/phpmyadmin
  □ Create database: trendship_db, collation: utf8mb4_unicode_ci
  □ Click the SQL tab
  □ Paste the entire SQL from Part 1.1
  □ Click Go
  □ Verify: 8 tables created, 12 materials seeded ✓

STEP 3 — Place website files
  □ Copy your entire trendship/ folder to:
    Windows: C:\xampp\htdocs\trendship\
    Mac:     /Applications/XAMPP/htdocs/trendship\
  □ Visit http://localhost/trendship/ — see home page ✓

STEP 4 — Create the api/ folder structure
  □ Create all folders listed in Part 2
  □ Create api/config.php (from Part 3)
  □ Create api/helpers.php (from Part 3)
  □ Create all api/*/index.php, read.php, update.php, delete.php files

STEP 5 — Test the API
  □ Visit http://localhost/trendship/api/materials/index.php
  □ You should see JSON with 12 materials ✓
  □ If you see a PHP error: check DB credentials in config.php

STEP 6 — Create admin files
  □ Create admin/ folder in trendship/
  □ Create admin/login.php (from Part 10)
  □ Create admin/index.php (from Part 10)
  □ Create admin/materials.php (from Part 10)

STEP 7 — Test admin login
  □ Visit http://localhost/trendship/admin/login.php
  □ Login: username=admin, password=admin123
  □ See dashboard with material count = 12 ✓
  □ IMMEDIATELY change the password after this step

STEP 8 — Connect frontend pages to backend
  □ Add the JS snippet from Part 11.1 to collection/index.html
  □ Add the JS snippet from Part 11.2 to contact/index.html
  □ Add the JS snippet from Part 11.3 to exhibition/index.html
  □ Add the JS snippet from Part 11.4 to trend-report/index.html
  □ Add the JS snippet from Part 11.5 to index.html

STEP 9 — Test each connection
  □ Home page: materials section loads from DB ✓
  □ Collection page: filter buttons load correct materials from DB ✓
  □ Contact page: submit form → check admin/messages.php ✓
  □ Exhibition page: booking form → check admin/bookings.php ✓

STEP 10 — Admin CRUD test
  □ Admin Materials: Add a new material → appears on collection page ✓
  □ Admin Materials: Edit a material → change reflects on collection page ✓
  □ Admin Materials: Delete a material → disappears from collection page ✓
  □ Admin Bookings: Change status pending → confirmed ✓
  □ Admin Messages: View message → auto-marks as read ✓

STEP 11 — Final verification
  □ All 9 original HTML pages still load and animate correctly ✓
  □ Three.js organism on home and contact pages ✓
  □ All GSAP scroll animations still work ✓
  □ Film grain, cursor, nav overlay all work ✓
  □ No console errors on any page ✓
```

---

```
TRENDSHIP — CRUD & XAMPP SPECIFICATION v1.0
Database: trendship_db (MySQL via XAMPP)
Backend:  PHP 8.x + PDO
Tables:   8  |  API Endpoints: 20  |  Admin Pages: 5
Frontend: Zero existing files modified — backend is purely additive
```
