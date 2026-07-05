<?php
require_once dirname(__DIR__) . '/api/config.php';
require_once dirname(__DIR__) . '/api/helpers.php';
initAdminSession();
session_unset();
session_destroy();
header('Location: ' . BASE_URL . '/admin/login.html');
exit();
