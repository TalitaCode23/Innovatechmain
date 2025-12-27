<?php
session_start();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../vendor/Core/Acl.php';

if (!isset($_SESSION['role'])) {
    header("Location: acesso-negado.php");
    exit;
}

$acl = new Acl($pdo, $_SESSION['role']);