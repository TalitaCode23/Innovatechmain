<?php
require_once '../Includes/acl.php';
require_once '../Includes/db_connect.php';

if (!$acl->pode('GERENCIAR_USUARIOS')) {
    exit;
}

$id = $_POST['id'] ?? null;

if ($id) {
    $pdo->prepare("
        UPDATE usuarios 
        SET ativo = 0 
        WHERE id = ?
    ")->execute([$id]);
}
