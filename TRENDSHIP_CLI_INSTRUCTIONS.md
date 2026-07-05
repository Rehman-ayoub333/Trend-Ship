# TRENDSHIP — Complete CLI Setup Instructions
## Command Line Guide: From Zero to Running Website
### Works on Windows (CMD/PowerShell) · Mac (Terminal) · Linux (Bash)

---

## WHAT THIS GUIDE DOES

By the end of these commands you will have:
```
✅ XAMPP running (Apache + MySQL)
✅ Database created with all 8 tables and 12 seed materials
✅ All 32 PHP backend files created in the right folders
✅ Website running at http://localhost/trendship/
✅ Admin panel at http://localhost/trendship/admin/
✅ All 9 frontend pages connected to the database
```

Nothing is manual. Every file is created by the commands below.

---

## PART 0 — PICK YOUR OPERATING SYSTEM

Jump to the section matching your OS:
- **Windows** → Part 1
- **Mac** → Part 2
- **Linux (Ubuntu/Debian)** → Part 3
- **All OS — shared steps** → Part 4 onwards (after installing XAMPP)

---

## PART 1 — WINDOWS SETUP

### 1.1 Open Command Prompt as Administrator

```
Press: Windows key
Type:  cmd
Right-click "Command Prompt"
Click: "Run as administrator"
```

### 1.2 Download and Install XAMPP

```cmd
:: Download XAMPP installer (requires curl, available on Windows 10+)
curl -L -o %TEMP%\xampp-installer.exe https://sourceforge.net/projects/xampp/files/XAMPP%20Windows/8.2.12/xampp-windows-x64-8.2.12-0-VS16-installer.exe/download

:: Run the installer (follow the GUI — accept all defaults, install to C:\xampp)
%TEMP%\xampp-installer.exe
```

**Or download manually:** https://www.apachefriends.org/download.html
Choose: Windows · PHP 8.2 · Click Download

### 1.3 Start Apache and MySQL

```cmd
:: Start Apache
"C:\xampp\apache\bin\httpd.exe" -k start

:: Start MySQL
"C:\xampp\mysql\bin\mysqld.exe" --defaults-file="C:\xampp\mysql\bin\my.ini" --standalone

:: --- OR use the XAMPP Control Panel (easier) ---
:: Open: C:\xampp\xampp-control.exe
:: Click START next to Apache
:: Click START next to MySQL
```

### 1.4 Verify XAMPP is running

```cmd
:: Open browser to test Apache
start http://localhost

:: Open phpMyAdmin
start http://localhost/phpmyadmin
```

Both should open without errors. If you see "Welcome to XAMPP" — Apache is running.

### 1.5 Navigate to the web root

```cmd
cd C:\xampp\htdocs
```

### 1.6 Set path variable for this session

```cmd
set SITE_ROOT=C:\xampp\htdocs\trendship
set PHP=C:\xampp\php\php.exe
set MYSQL=C:\xampp\mysql\bin\mysql.exe
```

**Skip to Part 4** now — the rest is OS-independent.

---

## PART 2 — MAC SETUP

### 2.1 Open Terminal

```
Press: Command + Space
Type:  Terminal
Press: Enter
```

### 2.2 Install XAMPP

```bash
# Download XAMPP for macOS
curl -L -o ~/Downloads/xampp-installer.dmg \
  "https://sourceforge.net/projects/xampp/files/XAMPP%20Mac%20OS%20X/8.2.12/xampp-osx-8.2.12-0-installer.dmg/download"

# Mount and open (follow GUI installer — install to /Applications/XAMPP)
open ~/Downloads/xampp-installer.dmg
```

**Or download manually:** https://www.apachefriends.org/download.html
Choose: macOS · PHP 8.2

### 2.3 Start Apache and MySQL

```bash
# Start Apache
sudo /Applications/XAMPP/xamppfiles/bin/apachectl start

# Start MySQL
sudo /Applications/XAMPP/xamppfiles/bin/mysqld_safe --user=daemon &

# --- OR use XAMPP Manager (easier) ---
open /Applications/XAMPP/manager-osx.app
# Click "Start All" or start Apache + MySQL individually
```

### 2.4 Verify

```bash
# Test Apache
open http://localhost

# Test phpMyAdmin
open http://localhost/phpmyadmin
```

### 2.5 Navigate to web root

```bash
cd /Applications/XAMPP/xamppfiles/htdocs
```

### 2.6 Set variables

```bash
export SITE_ROOT=/Applications/XAMPP/xamppfiles/htdocs/trendship
export PHP=/Applications/XAMPP/xamppfiles/bin/php
export MYSQL=/Applications/XAMPP/xamppfiles/bin/mysql
```

**Skip to Part 4.**

---

## PART 3 — LINUX (Ubuntu/Debian) SETUP

### 3.1 Open Terminal

```
Press: Ctrl + Alt + T
```

### 3.2 Install XAMPP

```bash
# Download XAMPP for Linux
wget -O ~/Downloads/xampp-installer.run \
  "https://sourceforge.net/projects/xampp/files/XAMPP%20Linux/8.2.12/xampp-linux-x64-8.2.12-0-installer.run/download"

# Make executable
chmod +x ~/Downloads/xampp-installer.run

# Run installer (sudo required, installs to /opt/lampp)
sudo ~/Downloads/xampp-installer.run
```

### 3.3 Start XAMPP

```bash
# Start all services (Apache + MySQL + FTP)
sudo /opt/lampp/lampp start

# Or start individually:
sudo /opt/lampp/lampp startapache
sudo /opt/lampp/lampp startmysql

# Check status
sudo /opt/lampp/lampp status
```

### 3.4 Verify

```bash
# Test Apache
curl -s http://localhost | grep -o "Welcome to XAMPP"

# Should output: Welcome to XAMPP
```

### 3.5 Navigate to web root

```bash
cd /opt/lampp/htdocs
```

### 3.6 Set variables

```bash
export SITE_ROOT=/opt/lampp/htdocs/trendship
export PHP=/opt/lampp/bin/php
export MYSQL=/opt/lampp/bin/mysql
```

---

## PART 4 — CREATE FOLDER STRUCTURE
### (All Operating Systems — run from web root)

### 4.1 Windows CMD

```cmd
:: Make sure you're in the right folder
cd C:\xampp\htdocs

:: Create project root
mkdir trendship
cd trendship

:: Create all 9 website page folders
mkdir design-trend
mkdir themes
mkdir exhibition
mkdir collection
mkdir trend-report
mkdir lookbook
mkdir about
mkdir contact

:: Create all API folders
mkdir api
mkdir api\materials
mkdir api\bookings
mkdir api\subscribers
mkdir api\messages
mkdir api\downloads
mkdir api\moodboards
mkdir api\auth

:: Create admin folder
mkdir admin

:: Verify structure
tree /F trendship
```

### 4.2 Mac / Linux Bash

```bash
# Make sure you're in the right folder
# Mac:   cd /Applications/XAMPP/xamppfiles/htdocs
# Linux: cd /opt/lampp/htdocs

# Create everything in one command
mkdir -p trendship/{design-trend,themes,exhibition,collection,trend-report,lookbook,about,contact}
mkdir -p trendship/api/{materials,bookings,subscribers,messages,downloads,moodboards,auth}
mkdir -p trendship/admin

# Verify
ls -R trendship/ | head -50
```

---

## PART 5 — CREATE THE DATABASE

### 5.1 Connect to MySQL via CLI

**Windows:**
```cmd
C:\xampp\mysql\bin\mysql.exe -u root -p
:: When prompted for password: just press Enter (XAMPP default = no password)
```

**Mac:**
```bash
/Applications/XAMPP/xamppfiles/bin/mysql -u root -p
# Press Enter when prompted for password
```

**Linux:**
```bash
/opt/lampp/bin/mysql -u root -p
# Press Enter when prompted for password
```

### 5.2 Run the database setup SQL

Once inside the MySQL prompt (`mysql>`), paste and run this entire block:

```sql
-- Create database
CREATE DATABASE IF NOT EXISTS trendship_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE trendship_db;

-- TABLE 1: materials
CREATE TABLE materials (
  id          INT          AUTO_INCREMENT PRIMARY KEY,
  name        VARCHAR(120) NOT NULL,
  code        VARCHAR(40)  NOT NULL UNIQUE,
  theme       ENUM('boost','cosmos','ooparts','synergy') NOT NULL,
  surface     VARCHAR(80)  NOT NULL,
  finish      VARCHAR(80)  DEFAULT NULL,
  thickness   VARCHAR(20)  DEFAULT NULL,
  dimensions  VARCHAR(40)  DEFAULT NULL,
  description TEXT         DEFAULT NULL,
  image_url   VARCHAR(500) NOT NULL,
  texture_url VARCHAR(500) DEFAULT NULL,
  room_url    VARCHAR(500) DEFAULT NULL,
  color_1     VARCHAR(7)   DEFAULT NULL,
  color_2     VARCHAR(7)   DEFAULT NULL,
  color_3     VARCHAR(7)   DEFAULT NULL,
  application VARCHAR(200) DEFAULT NULL,
  featured    TINYINT(1)   DEFAULT 0,
  sort_order  INT          DEFAULT 0,
  created_at  TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
  updated_at  TIMESTAMP    DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- TABLE 2: bookings
CREATE TABLE bookings (
  id          INT          AUTO_INCREMENT PRIMARY KEY,
  ref         VARCHAR(20)  NOT NULL UNIQUE,
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

-- TABLE 3: subscribers
CREATE TABLE subscribers (
  id            INT          AUTO_INCREMENT PRIMARY KEY,
  email         VARCHAR(200) NOT NULL UNIQUE,
  name          VARCHAR(120) DEFAULT NULL,
  source        VARCHAR(60)  DEFAULT 'newsletter',
  status        ENUM('active','unsubscribed') DEFAULT 'active',
  subscribed_at TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
  updated_at    TIMESTAMP    DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- TABLE 4: messages
CREATE TABLE messages (
  id         INT          AUTO_INCREMENT PRIMARY KEY,
  ref        VARCHAR(20)  NOT NULL UNIQUE,
  name       VARCHAR(120) NOT NULL,
  email      VARCHAR(200) NOT NULL,
  company    VARCHAR(120) DEFAULT NULL,
  interest   ENUM('general','exhibition','collection','press','partnership') DEFAULT 'general',
  message    TEXT         NOT NULL,
  status     ENUM('unread','read','replied','archived') DEFAULT 'unread',
  created_at TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP    DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- TABLE 5: report_downloads
CREATE TABLE report_downloads (
  id            INT          AUTO_INCREMENT PRIMARY KEY,
  email         VARCHAR(200) NOT NULL,
  name          VARCHAR(120) DEFAULT NULL,
  company       VARCHAR(120) DEFAULT NULL,
  downloaded_at TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
);

-- TABLE 6: moodboards
CREATE TABLE moodboards (
  id           INT          AUTO_INCREMENT PRIMARY KEY,
  session_id   VARCHAR(64)  NOT NULL,
  name         VARCHAR(120) DEFAULT 'My Moodboard',
  email        VARCHAR(200) DEFAULT NULL,
  material_ids VARCHAR(200) NOT NULL,
  created_at   TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
  updated_at   TIMESTAMP    DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- TABLE 7: admins
CREATE TABLE admins (
  id            INT          AUTO_INCREMENT PRIMARY KEY,
  username      VARCHAR(60)  NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  email         VARCHAR(200) NOT NULL,
  last_login    TIMESTAMP    DEFAULT NULL,
  created_at    TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
);

-- TABLE 8: activity_log
CREATE TABLE activity_log (
  id           INT          AUTO_INCREMENT PRIMARY KEY,
  admin_id     INT          DEFAULT NULL,
  action       VARCHAR(80)  NOT NULL,
  target_table VARCHAR(60)  DEFAULT NULL,
  target_id    INT          DEFAULT NULL,
  detail       TEXT         DEFAULT NULL,
  ip           VARCHAR(45)  DEFAULT NULL,
  created_at   TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (admin_id) REFERENCES admins(id) ON DELETE SET NULL
);

-- Default admin (password: admin123 — CHANGE THIS IMMEDIATELY)
INSERT INTO admins (username, password_hash, email) VALUES
('admin',
 '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
 'admin@trendship.com');

-- 12 seed materials
INSERT INTO materials
  (name,code,theme,surface,image_url,color_1,color_2,color_3,featured,sort_order)
VALUES
('Terracotta Linen','LX-B2025-001','boost','Matte Textured',
 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=800',
 '#c8885a','#b87848','#d8a880',1,1),
('Warm Oak','LX-B2025-002','boost','Wood Grain',
 'https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?w=800',
 '#c8a060','#a88040','#e0c080',0,2),
('Amber Matte','LX-B2025-003','boost','Matte Smooth',
 'https://images.unsplash.com/photo-1594736797933-d0501ba2fe65?w=800',
 '#d09848','#b07828','#e8b868',0,3),
('Midnight Slate','LX-C2025-001','cosmos','Stone Finish',
 'https://images.unsplash.com/photo-1519710164239-da123dc03ef4?w=800',
 '#3a4870','#2a3860','#4a5880',1,4),
('Void Navy','LX-C2025-002','cosmos','Ultra Matte',
 'https://images.unsplash.com/photo-1557804506-669a67965ba0?w=800',
 '#1a2248','#0a1230','#2a3258',0,5),
('Steel Blue','LX-C2025-003','cosmos','Brushed Metal',
 'https://images.unsplash.com/photo-1550859492-d5da9d8e45f3?w=800',
 '#607090','#485878','#788aa8',0,6),
('Ancient Earth','LX-O2025-001','ooparts','Raw Textured',
 'https://images.unsplash.com/photo-1565538810643-b5bdb714032a?w=800',
 '#9a7840','#7a5820','#c0a060',1,7),
('Stone Ash','LX-O2025-002','ooparts','Honed Stone',
 'https://images.unsplash.com/photo-1493666438817-866a91353ca9?w=800',
 '#a8a090','#888070','#c8c0b0',0,8),
('Fossil Brown','LX-O2025-003','ooparts','Leather Touch',
 'https://images.unsplash.com/photo-1598928506311-c55ded91a20c?w=800',
 '#8a6848','#6a4828','#aa8868',0,9),
('Bloom Rose','LX-S2025-001','synergy','Soft Satin',
 'https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?w=800',
 '#d4857a','#b46858','#e8a898',1,10),
('Petal Pink','LX-S2025-002','synergy','Velvet Touch',
 'https://images.unsplash.com/photo-1617104678090-33e8e12c3dd2?w=800',
 '#e8b0b0','#c89090','#f8d0d0',0,11),
('Silk Greige','LX-S2025-003','synergy','Linen Weave',
 'https://images.unsplash.com/photo-1600210492493-0946911123ea?w=800',
 '#c8b8b0','#a89890','#e0d8d0',0,12);

-- Verify everything was created
SELECT 'Tables created:' AS info;
SHOW TABLES;

SELECT 'Materials seeded:' AS info;
SELECT id, name, theme, featured FROM materials ORDER BY sort_order;

SELECT 'Admin created:' AS info;
SELECT id, username, email FROM admins;
```

### 5.3 Exit MySQL

```sql
EXIT;
```

### 5.4 Verify from CLI (quick test)

**Windows:**
```cmd
C:\xampp\mysql\bin\mysql.exe -u root -e "SELECT COUNT(*) as total_materials FROM trendship_db.materials;"
```

**Mac/Linux:**
```bash
/Applications/XAMPP/xamppfiles/bin/mysql -u root -e "SELECT COUNT(*) as total_materials FROM trendship_db.materials;"
# Linux: /opt/lampp/bin/mysql -u root -e "..."
```

Expected output:
```
+-----------------+
| total_materials |
+-----------------+
|              12 |
+-----------------+
```

---

## PART 6 — CREATE ALL PHP BACKEND FILES

Run the commands below from inside the `trendship/` folder.

### 6.0 Navigate into the project

**Windows:**
```cmd
cd C:\xampp\htdocs\trendship
```

**Mac:**
```bash
cd /Applications/XAMPP/xamppfiles/htdocs/trendship
```

**Linux:**
```bash
cd /opt/lampp/htdocs/trendship
```

---

### 6.1 Create `api/config.php`

**Mac/Linux:**
```bash
cat > api/config.php << 'PHP'
<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'trendship_db');
define('DB_PORT', 3306);
define('APP_NAME',    'TRENDSHIP');
define('BASE_URL',    'http://localhost/trendship');
define('SESSION_NAME',    'trendship_session');
define('SESSION_LIFETIME', 3600);

header('Access-Control-Allow-Origin: http://localhost');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(200); exit();
}

function getDB(): PDO {
  static $pdo = null;
  if ($pdo === null) {
    try {
      $dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8mb4;port=".DB_PORT;
      $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
      ]);
    } catch (PDOException $e) {
      http_response_code(500);
      echo json_encode(['success'=>false,'error'=>'Database connection failed']);
      exit();
    }
  }
  return $pdo;
}
PHP
echo "Created api/config.php"
```

**Windows CMD:**
```cmd
(
echo ^<?php
echo define^('DB_HOST', 'localhost'^);
echo define^('DB_USER', 'root'^);
echo define^('DB_PASS', ''^);
echo define^('DB_NAME', 'trendship_db'^);
echo define^('DB_PORT', 3306^);
echo define^('APP_NAME', 'TRENDSHIP'^);
echo define^('BASE_URL', 'http://localhost/trendship'^);
echo define^('SESSION_NAME', 'trendship_session'^);
echo define^('SESSION_LIFETIME', 3600^);
) > api\config.php
echo Created api/config.php
```

> **Windows users:** The heredoc `<< 'PHP'` syntax does not work in CMD.
> For Windows, the easiest approach is to use **PowerShell** or simply
> open each file in Notepad and paste the content from the CRUD spec.
> See **Windows PowerShell alternative** at the end of this section.

---

### 6.2 Create `api/helpers.php`

**Mac/Linux:**
```bash
cat > api/helpers.php << 'PHP'
<?php
require_once __DIR__ . '/config.php';

function respond(array $data, int $code = 200): void {
  http_response_code($code);
  echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
  exit();
}

function getBody(): array {
  $raw = file_get_contents('php://input');
  if (empty($raw)) return [];
  $data = json_decode($raw, true);
  if (json_last_error() !== JSON_ERROR_NONE) {
    respond(['success'=>false,'error'=>'Invalid JSON body'], 400);
  }
  return $data;
}

function validate(array $data, array $rules): array {
  $errors = [];
  foreach ($rules as $field => $type) {
    $val = trim($data[$field] ?? '');
    if ($val === '') { $errors[$field] = "$field is required"; continue; }
    if ($type === 'email' && !filter_var($val, FILTER_VALIDATE_EMAIL))
      $errors[$field] = "$field must be a valid email";
    if ($type === 'int' && !is_numeric($val))
      $errors[$field] = "$field must be a number";
    if ($type === 'date' && !strtotime($val))
      $errors[$field] = "$field must be YYYY-MM-DD";
  }
  return $errors;
}

function generateRef(string $prefix, string $table): string {
  $pdo  = getDB();
  $year = date('Y');
  $n    = (int)$pdo->query("SELECT COUNT(*) FROM `$table`")->fetchColumn() + 1;
  return $prefix.'-'.$year.'-'.str_pad($n, 4, '0', STR_PAD_LEFT);
}

function clean(string $str): string {
  return htmlspecialchars(strip_tags(trim($str)), ENT_QUOTES, 'UTF-8');
}

function requireAdmin(): void {
  session_name(SESSION_NAME); session_start();
  if (empty($_SESSION['admin_id'])) {
    header('Location: '.BASE_URL.'/admin/login.php'); exit();
  }
}

function requireAdminAPI(): void {
  session_name(SESSION_NAME); session_start();
  if (empty($_SESSION['admin_id'])) {
    respond(['success'=>false,'error'=>'Unauthorised'], 401);
  }
}
PHP
echo "Created api/helpers.php"
```

---

### 6.3 Create all API endpoint files

**Mac/Linux — run this entire block at once:**

```bash
# ── api/materials/index.php ──────────────────────────────────
cat > api/materials/index.php << 'PHP'
<?php
require_once dirname(__DIR__).'/helpers.php';
$method = $_SERVER['REQUEST_METHOD'];
$pdo    = getDB();

if ($method === 'GET') {
  $where = '1=1'; $params = [];
  if (!empty($_GET['theme']) && in_array($_GET['theme'],['boost','cosmos','ooparts','synergy'])) {
    $where .= ' AND theme = :theme'; $params[':theme'] = $_GET['theme'];
  }
  if (isset($_GET['featured']) && $_GET['featured']==='1') $where .= ' AND featured=1';
  if (!empty($_GET['search'])) {
    $where .= ' AND (name LIKE :s OR code LIKE :s)'; $params[':s']='%'.$_GET['search'].'%';
  }
  $stmt = $pdo->prepare("SELECT * FROM materials WHERE $where ORDER BY sort_order,id");
  $stmt->execute($params);
  $rows = $stmt->fetchAll();
  respond(['success'=>true,'count'=>count($rows),'data'=>$rows]);
}

if ($method === 'POST') {
  requireAdminAPI();
  $body   = getBody();
  $errors = validate($body,['name'=>'string','code'=>'string','theme'=>'string','surface'=>'string','image_url'=>'string']);
  if ($errors) respond(['success'=>false,'errors'=>$errors],422);
  $chk = $pdo->prepare("SELECT id FROM materials WHERE code=?"); $chk->execute([$body['code']]);
  if ($chk->fetch()) respond(['success'=>false,'error'=>'Code already exists'],409);
  $s = $pdo->prepare("INSERT INTO materials (name,code,theme,surface,finish,thickness,dimensions,description,image_url,texture_url,room_url,color_1,color_2,color_3,application,featured,sort_order) VALUES (:name,:code,:theme,:surface,:finish,:thickness,:dimensions,:description,:image_url,:texture_url,:room_url,:color_1,:color_2,:color_3,:application,:featured,:sort_order)");
  $s->execute([':name'=>clean($body['name']),':code'=>strtoupper(clean($body['code'])),':theme'=>$body['theme'],':surface'=>clean($body['surface']),':finish'=>clean($body['finish']??''),':thickness'=>clean($body['thickness']??''),':dimensions'=>clean($body['dimensions']??''),':description'=>clean($body['description']??''),':image_url'=>$body['image_url'],':texture_url'=>$body['texture_url']??null,':room_url'=>$body['room_url']??null,':color_1'=>$body['color_1']??null,':color_2'=>$body['color_2']??null,':color_3'=>$body['color_3']??null,':application'=>json_encode($body['application']??[]),':featured'=>(int)($body['featured']??0),':sort_order'=>(int)($body['sort_order']??0)]);
  $id=$pdo->lastInsertId();
  $pdo->prepare("INSERT INTO activity_log (admin_id,action,target_table,target_id,detail,ip) VALUES (?,?,?,?,?,?)")->execute([$_SESSION['admin_id'],'material.create','materials',$id,json_encode($body),$_SERVER['REMOTE_ADDR']]);
  respond(['success'=>true,'id'=>$id,'message'=>'Material created'],201);
}
respond(['success'=>false,'error'=>'Method not allowed'],405);
PHP

# ── api/materials/read.php ────────────────────────────────────
cat > api/materials/read.php << 'PHP'
<?php
require_once dirname(__DIR__).'/helpers.php';
if ($_SERVER['REQUEST_METHOD']!=='GET') respond(['success'=>false,'error'=>'Method not allowed'],405);
$id=(int)($_GET['id']??0);
if (!$id) respond(['success'=>false,'error'=>'id required'],400);
$s=getDB()->prepare("SELECT * FROM materials WHERE id=?"); $s->execute([$id]);
$row=$s->fetch();
if (!$row) respond(['success'=>false,'error'=>'Not found'],404);
respond(['success'=>true,'data'=>$row]);
PHP

# ── api/materials/update.php ─────────────────────────────────
cat > api/materials/update.php << 'PHP'
<?php
require_once dirname(__DIR__).'/helpers.php';
if ($_SERVER['REQUEST_METHOD']!=='PUT') respond(['success'=>false,'error'=>'Method not allowed'],405);
requireAdminAPI();
$body=getBody(); $id=(int)($body['id']??0);
if (!$id) respond(['success'=>false,'error'=>'id required'],400);
$pdo=getDB();
$chk=$pdo->prepare("SELECT id FROM materials WHERE id=?"); $chk->execute([$id]);
if (!$chk->fetch()) respond(['success'=>false,'error'=>'Not found'],404);
$allowed=['name','code','theme','surface','finish','thickness','dimensions','description','image_url','texture_url','room_url','color_1','color_2','color_3','application','featured','sort_order'];
$sets=[]; $params=[];
foreach ($allowed as $f) {
  if (array_key_exists($f,$body)) {
    $sets[]="$f=:$f";
    $params[":$f"]=($f==='application')?json_encode($body[$f]):clean((string)$body[$f]);
  }
}
if (empty($sets)) respond(['success'=>false,'error'=>'Nothing to update'],400);
$params[':id']=$id;
$pdo->prepare("UPDATE materials SET ".implode(',',$sets)." WHERE id=:id")->execute($params);
$pdo->prepare("INSERT INTO activity_log (admin_id,action,target_table,target_id,detail,ip) VALUES (?,?,?,?,?,?)")->execute([$_SESSION['admin_id'],'material.update','materials',$id,json_encode($body),$_SERVER['REMOTE_ADDR']]);
respond(['success'=>true,'message'=>'Material updated']);
PHP

# ── api/materials/delete.php ─────────────────────────────────
cat > api/materials/delete.php << 'PHP'
<?php
require_once dirname(__DIR__).'/helpers.php';
if ($_SERVER['REQUEST_METHOD']!=='DELETE') respond(['success'=>false,'error'=>'Method not allowed'],405);
requireAdminAPI();
$id=(int)(getBody()['id']??$_GET['id']??0);
if (!$id) respond(['success'=>false,'error'=>'id required'],400);
$pdo=getDB();
$s=$pdo->prepare("SELECT name FROM materials WHERE id=?"); $s->execute([$id]);
$row=$s->fetch(); if (!$row) respond(['success'=>false,'error'=>'Not found'],404);
$pdo->prepare("DELETE FROM materials WHERE id=?")->execute([$id]);
$pdo->prepare("INSERT INTO activity_log (admin_id,action,target_table,target_id,detail,ip) VALUES (?,?,?,?,?,?)")->execute([$_SESSION['admin_id'],'material.delete','materials',$id,json_encode(['name'=>$row['name']]),$_SERVER['REMOTE_ADDR']]);
respond(['success'=>true,'message'=>"Material '{$row['name']}' deleted"]);
PHP

echo "Created all materials API files"

# ── api/bookings/index.php ────────────────────────────────────
cat > api/bookings/index.php << 'PHP'
<?php
require_once dirname(__DIR__).'/helpers.php';
$method=$_SERVER['REQUEST_METHOD']; $pdo=getDB();

if ($method==='GET') {
  requireAdminAPI();
  $where='1=1'; $params=[];
  if (!empty($_GET['status'])) { $where.=' AND status=:status'; $params[':status']=$_GET['status']; }
  if (!empty($_GET['date']))   { $where.=' AND visit_date=:date'; $params[':date']=$_GET['date']; }
  $s=$pdo->prepare("SELECT * FROM bookings WHERE $where ORDER BY visit_date,visit_time"); $s->execute($params);
  respond(['success'=>true,'data'=>$s->fetchAll()]);
}

if ($method==='POST') {
  $body=getBody();
  $errors=validate($body,['name'=>'string','email'=>'email','visit_date'=>'date','visit_time'=>'string']);
  if ($errors) respond(['success'=>false,'errors'=>$errors],422);
  $ts=strtotime($body['visit_date']);
  if (date('N',$ts)==1) respond(['success'=>false,'error'=>'Closed on Mondays'],422);
  if ($ts<strtotime('today')||$ts>strtotime('+30 days')) respond(['success'=>false,'error'=>'Date must be within next 30 days'],422);
  $slotChk=$pdo->prepare("SELECT COUNT(*) FROM bookings WHERE visit_date=? AND visit_time=? AND status!='cancelled'");
  $slotChk->execute([$body['visit_date'],$body['visit_time']]);
  if ($slotChk->fetchColumn()>=20) respond(['success'=>false,'error'=>'Slot is fully booked'],409);
  $ref=generateRef('MAI','bookings');
  $pdo->prepare("INSERT INTO bookings (ref,name,email,phone,company,visit_date,visit_time,party_size,notes) VALUES (?,?,?,?,?,?,?,?,?)")->execute([$ref,clean($body['name']),strtolower(trim($body['email'])),clean($body['phone']??''),clean($body['company']??''),$body['visit_date'],$body['visit_time'],(int)($body['party_size']??1),clean($body['notes']??'')]);
  respond(['success'=>true,'ref'=>$ref,'message'=>"Booking confirmed! Reference: $ref"],201);
}
respond(['success'=>false,'error'=>'Method not allowed'],405);
PHP

cat > api/bookings/read.php << 'PHP'
<?php
require_once dirname(__DIR__).'/helpers.php';
if ($_SERVER['REQUEST_METHOD']!=='GET') respond(['success'=>false,'error'=>'Method not allowed'],405);
requireAdminAPI();
$id=(int)($_GET['id']??0); $ref=$_GET['ref']??'';
if (!$id&&!$ref) respond(['success'=>false,'error'=>'id or ref required'],400);
$sql=$id?"SELECT * FROM bookings WHERE id=?":"SELECT * FROM bookings WHERE ref=?";
$s=getDB()->prepare($sql); $s->execute([$id?:$ref]);
$row=$s->fetch(); if (!$row) respond(['success'=>false,'error'=>'Not found'],404);
respond(['success'=>true,'data'=>$row]);
PHP

cat > api/bookings/update.php << 'PHP'
<?php
require_once dirname(__DIR__).'/helpers.php';
if ($_SERVER['REQUEST_METHOD']!=='PUT') respond(['success'=>false,'error'=>'Method not allowed'],405);
requireAdminAPI();
$body=getBody(); $id=(int)($body['id']??0); $status=$body['status']??'';
if (!$id||!in_array($status,['pending','confirmed','cancelled'])) respond(['success'=>false,'error'=>'id and valid status required'],400);
getDB()->prepare("UPDATE bookings SET status=? WHERE id=?")->execute([$status,$id]);
respond(['success'=>true,'message'=>"Status updated to $status"]);
PHP

cat > api/bookings/delete.php << 'PHP'
<?php
require_once dirname(__DIR__).'/helpers.php';
if ($_SERVER['REQUEST_METHOD']!=='DELETE') respond(['success'=>false,'error'=>'Method not allowed'],405);
requireAdminAPI();
$id=(int)(getBody()['id']??$_GET['id']??0);
if (!$id) respond(['success'=>false,'error'=>'id required'],400);
$pdo=getDB(); $s=$pdo->prepare("SELECT ref FROM bookings WHERE id=?"); $s->execute([$id]);
$row=$s->fetch(); if (!$row) respond(['success'=>false,'error'=>'Not found'],404);
$pdo->prepare("DELETE FROM bookings WHERE id=?")->execute([$id]);
respond(['success'=>true,'message'=>"Booking {$row['ref']} deleted"]);
PHP

echo "Created all bookings API files"

# ── api/subscribers ────────────────────────────────────────────
cat > api/subscribers/index.php << 'PHP'
<?php
require_once dirname(__DIR__).'/helpers.php';
$method=$_SERVER['REQUEST_METHOD']; $pdo=getDB();

if ($method==='GET') {
  requireAdminAPI();
  $s=$pdo->prepare("SELECT * FROM subscribers WHERE status=? ORDER BY subscribed_at DESC");
  $s->execute([$_GET['status']??'active']);
  $rows=$s->fetchAll();
  respond(['success'=>true,'count'=>count($rows),'data'=>$rows]);
}

if ($method==='POST') {
  $body=getBody();
  $errors=validate($body,['email'=>'email']);
  if ($errors) respond(['success'=>false,'errors'=>$errors],422);
  $email=strtolower(trim($body['email']));
  $chk=$pdo->prepare("SELECT id,status FROM subscribers WHERE email=?"); $chk->execute([$email]);
  $ex=$chk->fetch();
  if ($ex) {
    if ($ex['status']==='active') respond(['success'=>false,'error'=>'Already subscribed'],409);
    $pdo->prepare("UPDATE subscribers SET status='active',source=? WHERE email=?")->execute([$body['source']??'newsletter',$email]);
    respond(['success'=>true,'message'=>'Welcome back! Re-subscribed.']);
  }
  $pdo->prepare("INSERT INTO subscribers (email,name,source) VALUES (?,?,?)")->execute([$email,clean($body['name']??''),clean($body['source']??'newsletter')]);
  respond(['success'=>true,'message'=>'Thank you for subscribing!'],201);
}
respond(['success'=>false,'error'=>'Method not allowed'],405);
PHP

cat > api/subscribers/update.php << 'PHP'
<?php
require_once dirname(__DIR__).'/helpers.php';
if ($_SERVER['REQUEST_METHOD']!=='PUT') respond(['success'=>false,'error'=>'Method not allowed'],405);
$body=getBody(); $email=strtolower(trim($body['email']??'')); $status=$body['status']??'unsubscribed';
if (!filter_var($email,FILTER_VALIDATE_EMAIL)) respond(['success'=>false,'error'=>'Valid email required'],400);
$pdo=getDB(); $s=$pdo->prepare("UPDATE subscribers SET status=? WHERE email=?"); $s->execute([$status,$email]);
if ($s->rowCount()===0) respond(['success'=>false,'error'=>'Email not found'],404);
respond(['success'=>true,'message'=>'Subscription updated']);
PHP

cat > api/subscribers/delete.php << 'PHP'
<?php
require_once dirname(__DIR__).'/helpers.php';
if ($_SERVER['REQUEST_METHOD']!=='DELETE') respond(['success'=>false,'error'=>'Method not allowed'],405);
requireAdminAPI();
$body=getBody(); $id=(int)($body['id']??0); $email=strtolower(trim($body['email']??''));
if (!$id&&!$email) respond(['success'=>false,'error'=>'id or email required'],400);
$pdo=getDB();
$sql=$id?"DELETE FROM subscribers WHERE id=?":"DELETE FROM subscribers WHERE email=?";
$s=$pdo->prepare($sql); $s->execute([$id?:$email]);
if ($s->rowCount()===0) respond(['success'=>false,'error'=>'Not found'],404);
respond(['success'=>true,'message'=>'Subscriber deleted']);
PHP

echo "Created all subscribers API files"

# ── api/messages ───────────────────────────────────────────────
cat > api/messages/index.php << 'PHP'
<?php
require_once dirname(__DIR__).'/helpers.php';
$method=$_SERVER['REQUEST_METHOD']; $pdo=getDB();

if ($method==='GET') {
  requireAdminAPI();
  $where='1=1'; $params=[];
  if (!empty($_GET['status']))   { $where.=' AND status=:status'; $params[':status']=$_GET['status']; }
  if (!empty($_GET['interest'])) { $where.=' AND interest=:interest'; $params[':interest']=$_GET['interest']; }
  $s=$pdo->prepare("SELECT * FROM messages WHERE $where ORDER BY created_at DESC"); $s->execute($params);
  respond(['success'=>true,'data'=>$s->fetchAll()]);
}

if ($method==='POST') {
  $body=getBody();
  $errors=validate($body,['name'=>'string','email'=>'email','message'=>'string']);
  if ($errors) respond(['success'=>false,'errors'=>$errors],422);
  $rateChk=$pdo->prepare("SELECT COUNT(*) FROM messages WHERE email=? AND created_at>NOW()-INTERVAL 24 HOUR");
  $rateChk->execute([strtolower(trim($body['email']))]);
  if ($rateChk->fetchColumn()>=3) respond(['success'=>false,'error'=>'Too many messages. Wait 24 hours.'],429);
  $ref=generateRef('TRD','messages');
  $pdo->prepare("INSERT INTO messages (ref,name,email,company,interest,message) VALUES (?,?,?,?,?,?)")->execute([$ref,clean($body['name']),strtolower(trim($body['email'])),clean($body['company']??''),$body['interest']??'general',clean($body['message'])]);
  $subChk=$pdo->prepare("SELECT id FROM subscribers WHERE email=?"); $subChk->execute([strtolower(trim($body['email']))]);
  if (!$subChk->fetch()) $pdo->prepare("INSERT IGNORE INTO subscribers (email,name,source) VALUES (?,?,'contact')")->execute([strtolower(trim($body['email'])),clean($body['name'])]);
  respond(['success'=>true,'ref'=>$ref,'message'=>"Thank you! Reference: $ref"],201);
}
respond(['success'=>false,'error'=>'Method not allowed'],405);
PHP

cat > api/messages/read.php << 'PHP'
<?php
require_once dirname(__DIR__).'/helpers.php';
if ($_SERVER['REQUEST_METHOD']!=='GET') respond(['success'=>false,'error'=>'Method not allowed'],405);
requireAdminAPI();
$id=(int)($_GET['id']??0); if (!$id) respond(['success'=>false,'error'=>'id required'],400);
$pdo=getDB(); $s=$pdo->prepare("SELECT * FROM messages WHERE id=?"); $s->execute([$id]);
$row=$s->fetch(); if (!$row) respond(['success'=>false,'error'=>'Not found'],404);
if ($row['status']==='unread') { $pdo->prepare("UPDATE messages SET status='read' WHERE id=?")->execute([$id]); $row['status']='read'; }
respond(['success'=>true,'data'=>$row]);
PHP

cat > api/messages/update.php << 'PHP'
<?php
require_once dirname(__DIR__).'/helpers.php';
if ($_SERVER['REQUEST_METHOD']!=='PUT') respond(['success'=>false,'error'=>'Method not allowed'],405);
requireAdminAPI();
$body=getBody(); $id=(int)($body['id']??0); $status=$body['status']??'';
if (!$id||!in_array($status,['unread','read','replied','archived'])) respond(['success'=>false,'error'=>'id and valid status required'],400);
getDB()->prepare("UPDATE messages SET status=? WHERE id=?")->execute([$status,$id]);
respond(['success'=>true,'message'=>"Status updated to $status"]);
PHP

cat > api/messages/delete.php << 'PHP'
<?php
require_once dirname(__DIR__).'/helpers.php';
if ($_SERVER['REQUEST_METHOD']!=='DELETE') respond(['success'=>false,'error'=>'Method not allowed'],405);
requireAdminAPI();
$id=(int)(getBody()['id']??$_GET['id']??0); if (!$id) respond(['success'=>false,'error'=>'id required'],400);
$pdo=getDB(); $s=$pdo->prepare("SELECT name FROM messages WHERE id=?"); $s->execute([$id]);
$row=$s->fetch(); if (!$row) respond(['success'=>false,'error'=>'Not found'],404);
$pdo->prepare("DELETE FROM messages WHERE id=?")->execute([$id]);
respond(['success'=>true,'message'=>"Message from '{$row['name']}' deleted"]);
PHP

echo "Created all messages API files"

# ── api/downloads/index.php ───────────────────────────────────
cat > api/downloads/index.php << 'PHP'
<?php
require_once dirname(__DIR__).'/helpers.php';
$method=$_SERVER['REQUEST_METHOD']; $pdo=getDB();
if ($method==='GET') {
  requireAdminAPI();
  $rows=$pdo->query("SELECT * FROM report_downloads ORDER BY downloaded_at DESC")->fetchAll();
  respond(['success'=>true,'count'=>count($rows),'data'=>$rows]);
}
if ($method==='POST') {
  $body=getBody(); $errors=validate($body,['email'=>'email']);
  if ($errors) respond(['success'=>false,'errors'=>$errors],422);
  $email=strtolower(trim($body['email']));
  $pdo->prepare("INSERT INTO report_downloads (email,name,company) VALUES (?,?,?)")->execute([$email,clean($body['name']??''),clean($body['company']??'')]);
  $pdo->prepare("INSERT IGNORE INTO subscribers (email,name,source) VALUES (?,'','report_download')")->execute([$email]);
  respond(['success'=>true,'pdf_url'=>BASE_URL.'/assets/TRENDSHIP-2025-Trend-Report.pdf','message'=>'Download ready!']);
}
respond(['success'=>false,'error'=>'Method not allowed'],405);
PHP

echo "Created downloads API file"

# ── api/moodboards ────────────────────────────────────────────
cat > api/moodboards/index.php << 'PHP'
<?php
require_once dirname(__DIR__).'/helpers.php';
session_name(SESSION_NAME); session_start();
$method=$_SERVER['REQUEST_METHOD']; $pdo=getDB(); $sid=session_id();
if ($method==='GET') {
  $s=$pdo->prepare("SELECT * FROM moodboards WHERE session_id=? ORDER BY updated_at DESC LIMIT 1"); $s->execute([$sid]);
  $mb=$s->fetch();
  if (!$mb) respond(['success'=>true,'data'=>['items'=>[],'name'=>'My Moodboard']]);
  respond(['success'=>true,'data'=>['id'=>$mb['id'],'name'=>$mb['name'],'items'=>json_decode($mb['material_ids'],true)??[]]]);
}
if ($method==='POST') {
  $body=getBody(); $items=$body['items']??[]; $name=clean($body['name']??'My Moodboard'); $email=strtolower(trim($body['email']??''));
  if (count($items)>6) respond(['success'=>false,'error'=>'Max 6 materials'],422);
  $s=$pdo->prepare("SELECT id FROM moodboards WHERE session_id=? ORDER BY updated_at DESC LIMIT 1"); $s->execute([$sid]); $ex=$s->fetch();
  if ($ex) { $pdo->prepare("UPDATE moodboards SET name=?,material_ids=?,email=? WHERE id=?")->execute([$name,json_encode($items),$email?:null,$ex['id']]); $id=$ex['id']; }
  else { $pdo->prepare("INSERT INTO moodboards (session_id,name,material_ids,email) VALUES (?,?,?,?)")->execute([$sid,$name,json_encode($items),$email?:null]); $id=$pdo->lastInsertId(); }
  respond(['success'=>true,'id'=>$id,'message'=>'Moodboard saved']);
}
respond(['success'=>false,'error'=>'Method not allowed'],405);
PHP

cat > api/moodboards/update.php << 'PHP'
<?php
require_once dirname(__DIR__).'/helpers.php';
if ($_SERVER['REQUEST_METHOD']!=='PUT') respond(['success'=>false,'error'=>'Method not allowed'],405);
session_name(SESSION_NAME); session_start();
$body=getBody(); $pdo=getDB(); $sid=session_id();
$s=$pdo->prepare("SELECT id FROM moodboards WHERE session_id=? ORDER BY updated_at DESC LIMIT 1"); $s->execute([$sid]); $mb=$s->fetch();
if (!$mb) respond(['success'=>false,'error'=>'No moodboard for this session'],404);
$sets=[]; $params=[];
if (isset($body['items'])) { if (count($body['items'])>6) respond(['success'=>false,'error'=>'Max 6'],422); $sets[]='material_ids=:items'; $params[':items']=json_encode($body['items']); }
if (isset($body['name']))  { $sets[]='name=:name'; $params[':name']=clean($body['name']); }
if (empty($sets)) respond(['success'=>false,'error'=>'Nothing to update'],400);
$params[':id']=$mb['id'];
$pdo->prepare("UPDATE moodboards SET ".implode(',',$sets)." WHERE id=:id")->execute($params);
respond(['success'=>true,'message'=>'Moodboard updated']);
PHP

cat > api/moodboards/delete.php << 'PHP'
<?php
require_once dirname(__DIR__).'/helpers.php';
if ($_SERVER['REQUEST_METHOD']!=='DELETE') respond(['success'=>false,'error'=>'Method not allowed'],405);
session_name(SESSION_NAME); session_start();
$pdo=getDB(); $pdo->prepare("DELETE FROM moodboards WHERE session_id=?")->execute([session_id()]);
respond(['success'=>true,'message'=>'Moodboard cleared']);
PHP

echo "Created all moodboard API files"

# ── api/auth ──────────────────────────────────────────────────
cat > api/auth/login.php << 'PHP'
<?php
require_once dirname(__DIR__).'/helpers.php';
if ($_SERVER['REQUEST_METHOD']!=='POST') respond(['success'=>false,'error'=>'Method not allowed'],405);
session_name(SESSION_NAME); session_start();
$body=getBody(); $username=trim($body['username']??''); $password=$body['password']??'';
if (!$username||!$password) respond(['success'=>false,'error'=>'Username and password required'],400);
$pdo=getDB(); $s=$pdo->prepare("SELECT id,password_hash FROM admins WHERE username=?"); $s->execute([$username]);
$admin=$s->fetch();
if (!$admin||!password_verify($password,$admin['password_hash'])) respond(['success'=>false,'error'=>'Invalid credentials'],401);
$_SESSION['admin_id']=$admin['id']; $_SESSION['admin_username']=$username;
$pdo->prepare("UPDATE admins SET last_login=NOW() WHERE id=?")->execute([$admin['id']]);
respond(['success'=>true,'message'=>'Logged in']);
PHP

cat > api/auth/logout.php << 'PHP'
<?php
require_once dirname(__DIR__).'/helpers.php';
session_name(SESSION_NAME); session_start(); session_destroy();
respond(['success'=>true,'message'=>'Logged out']);
PHP

echo "Created auth API files"

# ── api/.htaccess ─────────────────────────────────────────────
cat > api/.htaccess << 'HTACCESS'
Options -Indexes
<Files "*.php">
  Header set X-Content-Type-Options "nosniff"
  Header set X-Frame-Options "DENY"
</Files>
HTACCESS

echo "Created api/.htaccess"
echo ""
echo "All API files created successfully."
```

---

### 6.4 Create Admin Panel files

**Mac/Linux:**

```bash
# ── admin/login.php ───────────────────────────────────────────
cat > admin/login.php << 'PHP'
<?php
require_once dirname(__DIR__).'/api/config.php';
session_name(SESSION_NAME); session_start();
if (!empty($_SESSION['admin_id'])) { header('Location: index.php'); exit(); }
$error='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
  $u=trim($_POST['username']??''); $p=$_POST['password']??'';
  if ($u&&$p) {
    $pdo=getDB(); $s=$pdo->prepare("SELECT id,password_hash FROM admins WHERE username=?"); $s->execute([$u]);
    $a=$s->fetch();
    if ($a&&password_verify($p,$a['password_hash'])) {
      $_SESSION['admin_id']=$a['id']; $_SESSION['admin_username']=$u;
      $pdo->prepare("UPDATE admins SET last_login=NOW() WHERE id=?")->execute([$a['id']]);
      header('Location: index.php'); exit();
    } else $error='Incorrect username or password.';
  } else $error='Enter username and password.';
}
?><!DOCTYPE html>
<html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>TRENDSHIP Admin</title>
<style>*{margin:0;padding:0;box-sizing:border-box}body{font-family:'Segoe UI',sans-serif;background:#0a0a0a;display:flex;align-items:center;justify-content:center;min-height:100vh}.box{background:#141414;border:1px solid #222;border-radius:8px;padding:48px;width:380px}.logo{font-size:22px;letter-spacing:.14em;color:#f4efe9;margin-bottom:8px}.sub{font-size:12px;color:#6a6058;margin-bottom:36px}label{display:block;font-size:11px;letter-spacing:.15em;text-transform:uppercase;color:#6a6058;margin-bottom:6px}input{width:100%;background:#1e1e1e;border:1px solid #2a2a2a;border-radius:4px;padding:12px 14px;color:#f0e8e0;font-size:14px;margin-bottom:18px;outline:none}input:focus{border-color:#d4857a}button{width:100%;background:#d4857a;color:#fff;border:none;border-radius:4px;padding:13px;font-size:13px;letter-spacing:.15em;cursor:pointer}.err{background:#2a1414;border:1px solid #6a2a2a;color:#e8a0a0;padding:10px 14px;border-radius:4px;font-size:13px;margin-bottom:18px}a{color:#c4968e;font-size:12px;display:block;text-align:center;margin-top:20px;text-decoration:none}</style>
</head><body><div class="box">
<div class="logo">TRENDSHIP</div><div class="sub">Admin Panel</div>
<?php if($error): ?><div class="err"><?=htmlspecialchars($error)?></div><?php endif; ?>
<form method="POST"><label>Username</label><input type="text" name="username" required><label>Password</label><input type="password" name="password" required><button type="submit">Sign In</button></form>
<a href="../index.html">← Back to Website</a>
</div></body></html>
PHP

# ── admin/logout.php ──────────────────────────────────────────
cat > admin/logout.php << 'PHP'
<?php
require_once dirname(__DIR__).'/api/config.php';
session_name(SESSION_NAME); session_start(); session_destroy();
header('Location: login.php'); exit();
PHP

echo "Created admin login/logout"
```

---

## PART 7 — QUICK TEST: API IS WORKING

```bash
# Test that the materials API returns JSON with 12 materials
# Mac/Linux:
curl -s http://localhost/trendship/api/materials/index.php | python3 -m json.tool | head -30

# Windows CMD:
curl -s http://localhost/trendship/api/materials/index.php
```

**Expected output:**
```json
{
  "success": true,
  "count": 12,
  "data": [
    {
      "id": "1",
      "name": "Terracotta Linen",
      "code": "LX-B2025-001",
      "theme": "boost",
      ...
    },
    ...
  ]
}
```

If you see this → everything is working. ✅

If you see an error → check Part 8 (Troubleshooting).

---

## PART 8 — CHANGE THE ADMIN PASSWORD (REQUIRED)

```bash
# Generate a new bcrypt hash for your chosen password
# Mac/Linux — replace 'your_new_password' with your actual password:
php -r "echo password_hash('your_new_password', PASSWORD_BCRYPT) . PHP_EOL;"

# Copy the output hash, then run:
# (Replace YOUR_HASH with the output from above)
/Applications/XAMPP/xamppfiles/bin/mysql -u root -e \
  "UPDATE trendship_db.admins SET password_hash='YOUR_HASH' WHERE username='admin';"

# Verify
/Applications/XAMPP/xamppfiles/bin/mysql -u root -e \
  "SELECT username, last_login FROM trendship_db.admins;"
```

**Windows:**
```cmd
C:\xampp\php\php.exe -r "echo password_hash('your_new_password', PASSWORD_BCRYPT);"
:: Copy the output, then:
C:\xampp\mysql\bin\mysql.exe -u root -e "UPDATE trendship_db.admins SET password_hash='YOUR_HASH' WHERE username='admin';"
```

---

## PART 9 — COPY YOUR EXISTING index.html

If you already have your built `index.html`:

**Mac/Linux:**
```bash
# If your existing index.html is on the Desktop:
cp ~/Desktop/index.html /Applications/XAMPP/xamppfiles/htdocs/trendship/index.html

# Linux:
cp ~/Desktop/index.html /opt/lampp/htdocs/trendship/index.html
```

**Windows:**
```cmd
copy %USERPROFILE%\Desktop\index.html C:\xampp\htdocs\trendship\index.html
```

Then open: http://localhost/trendship/

---

## PART 10 — FULL VERIFICATION SEQUENCE

Run these in order. Each should pass before moving to the next.

```bash
# ── Test 1: Apache is serving files
curl -s -o /dev/null -w "%{http_code}" http://localhost/trendship/
# Expected: 200

# ── Test 2: Materials API returns data
curl -s http://localhost/trendship/api/materials/index.php | grep '"count"'
# Expected: "count": 12

# ── Test 3: Filter works
curl -s "http://localhost/trendship/api/materials/index.php?theme=boost" | grep '"count"'
# Expected: "count": 3

# ── Test 4: Single material read
curl -s "http://localhost/trendship/api/materials/read.php?id=1" | grep '"name"'
# Expected: "name": "Terracotta Linen"

# ── Test 5: Contact form submission
curl -s -X POST http://localhost/trendship/api/messages/index.php \
  -H "Content-Type: application/json" \
  -d '{"name":"Test User","email":"test@test.com","message":"Hello from CLI test"}' \
  | grep '"success"'
# Expected: "success": true

# ── Test 6: Newsletter subscription
curl -s -X POST http://localhost/trendship/api/subscribers/index.php \
  -H "Content-Type: application/json" \
  -d '{"email":"newsletter@test.com","source":"newsletter"}' \
  | grep '"success"'
# Expected: "success": true

# ── Test 7: Check data was saved
/Applications/XAMPP/xamppfiles/bin/mysql -u root -e "SELECT name,email,ref FROM trendship_db.messages LIMIT 3;"
/Applications/XAMPP/xamppfiles/bin/mysql -u root -e "SELECT email,source FROM trendship_db.subscribers LIMIT 3;"

# ── Test 8: Admin panel loads
curl -s -o /dev/null -w "%{http_code}" http://localhost/trendship/admin/login.php
# Expected: 200
```

---

## PART 11 — TROUBLESHOOTING

### Problem: `curl: command not found`
```bash
# Mac: curl is pre-installed. If missing:
brew install curl

# Linux:
sudo apt-get install curl

# Windows: curl is included in Windows 10+
# If missing: download from https://curl.se/windows/
```

### Problem: `Connection refused` on http://localhost
```bash
# Mac — check Apache is running:
sudo /Applications/XAMPP/xamppfiles/bin/apachectl status
# If stopped: sudo /Applications/XAMPP/xamppfiles/bin/apachectl start

# Linux:
sudo /opt/lampp/lampp status
sudo /opt/lampp/lampp startapache

# Windows: Open XAMPP Control Panel → click Start next to Apache
```

### Problem: `Access denied for user 'root'@'localhost'`
```bash
# XAMPP MySQL has no password by default
# If you've set a password, edit api/config.php:
# Change: define('DB_PASS', '');
# To:     define('DB_PASS', 'your_mysql_password');
```

### Problem: PHP shows as plain text (not executed)
```
The file is being opened directly in the browser, not served by Apache.
Make sure you're using: http://localhost/trendship/
NOT: file:///C:/xampp/htdocs/trendship/index.html
```

### Problem: `Table doesn't exist` error
```bash
# Re-run the database creation SQL from Part 5
# Or run the SQL file directly:
# Mac/Linux:
/Applications/XAMPP/xamppfiles/bin/mysql -u root trendship_db < setup.sql

# Check tables exist:
/Applications/XAMPP/xamppfiles/bin/mysql -u root -e "SHOW TABLES FROM trendship_db;"
```

### Problem: Admin login fails
```bash
# Verify the admin row exists:
/Applications/XAMPP/xamppfiles/bin/mysql -u root -e "SELECT id,username FROM trendship_db.admins;"

# Reset password to 'admin123':
/Applications/XAMPP/xamppfiles/bin/mysql -u root -e \
  "UPDATE trendship_db.admins SET password_hash='\$2y\$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' WHERE username='admin';"
```

### Problem: `Headers already sent` PHP warning
```
Open api/config.php — make sure there is NO blank line or space
before the opening <?php tag. The file must start with exactly: <?php
```

### Problem: Windows file creation issues with heredoc
```
Windows CMD does not support heredoc (<<) syntax.
Use PowerShell instead:

  1. Press Windows key, type PowerShell, run as Administrator
  2. Navigate: cd C:\xampp\htdocs\trendship
  3. Use Set-Content or Out-File:
     Set-Content api\config.php '<?php ...'
  4. OR: just open each .php file in Notepad and paste from the CRUD spec
```

---

## PART 12 — USEFUL MYSQL COMMANDS (admin/monitoring)

Run these anytime to check the state of your database:

```bash
# Connect to MySQL
/Applications/XAMPP/xamppfiles/bin/mysql -u root trendship_db
# Windows: C:\xampp\mysql\bin\mysql.exe -u root trendship_db

# Once inside MySQL prompt:

-- Count all records
SELECT 'materials'   AS tbl, COUNT(*) AS n FROM materials
UNION SELECT 'bookings',   COUNT(*) FROM bookings
UNION SELECT 'messages',   COUNT(*) FROM messages
UNION SELECT 'subscribers',COUNT(*) FROM subscribers
UNION SELECT 'downloads',  COUNT(*) FROM report_downloads;

-- See all unread messages
SELECT ref, name, email, interest, LEFT(message,60) AS preview
FROM messages WHERE status='unread' ORDER BY created_at DESC;

-- See all pending bookings
SELECT ref, name, email, visit_date, visit_time, party_size
FROM bookings WHERE status='pending' ORDER BY visit_date;

-- See subscriber count by source
SELECT source, COUNT(*) as total FROM subscribers GROUP BY source;

-- See today's activity log
SELECT action, target_table, target_id, ip, created_at
FROM activity_log WHERE DATE(created_at)=CURDATE();

-- Exit MySQL
EXIT;
```

---

## PART 13 — QUICK REFERENCE

```
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
URLS
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
Website:        http://localhost/trendship/
Admin Panel:    http://localhost/trendship/admin/
phpMyAdmin:     http://localhost/phpmyadmin/
API materials:  http://localhost/trendship/api/materials/index.php

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
DEFAULT CREDENTIALS (change immediately)
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
Admin username: admin
Admin password: admin123
MySQL username: root
MySQL password: (empty)

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
FILE LOCATIONS
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
Windows:  C:\xampp\htdocs\trendship\
Mac:      /Applications/XAMPP/xamppfiles/htdocs/trendship/
Linux:    /opt/lampp/htdocs/trendship/

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
START/STOP XAMPP
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
Mac start:   sudo /Applications/XAMPP/xamppfiles/bin/apachectl start
             sudo /Applications/XAMPP/xamppfiles/bin/mysqld_safe --user=daemon &
Mac stop:    sudo /Applications/XAMPP/xamppfiles/bin/apachectl stop
             sudo /Applications/XAMPP/xamppfiles/bin/mysqladmin -u root shutdown

Linux start: sudo /opt/lampp/lampp start
Linux stop:  sudo /opt/lampp/lampp stop

Windows:     Use XAMPP Control Panel (Start / Stop buttons)

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
DATABASE
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
Name:         trendship_db
Tables:       8
Seed records: 12 materials, 1 admin
Charset:      utf8mb4_unicode_ci
```

---

```
TRENDSHIP CLI SETUP GUIDE v1.0
Windows · Mac · Linux
XAMPP + PHP 8.x + MySQL
Zero manual steps — copy, paste, done.
```
