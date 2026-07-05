<?php
require_once dirname(__DIR__) . '/helpers.php';

session_name(SESSION_NAME);
session_start();
session_destroy();
respond(['success' => true, 'message' => 'Logged out']);
