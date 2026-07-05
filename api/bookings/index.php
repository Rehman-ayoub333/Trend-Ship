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
