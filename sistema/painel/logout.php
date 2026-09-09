<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';

logout_gestor();
header('Location: /painel/login.php');
exit;
