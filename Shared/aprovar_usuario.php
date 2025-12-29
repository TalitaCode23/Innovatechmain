<?php
require_once '../Includes/acl.php';
require_once '../Includes/db_connect.php';

if (!$acl->pode('GERENCIAR_USUARIOS')) {
    http_response_code(403);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

$userId = $data['user_id'] ?? null;
$role   = $data['role'] ?? null;

if (!$userId || !$role) {
    http_response_code(400);
    exit;
}

$pdo->beginTransaction();

// ativa e aprova
$pdo->prepare("
    UPDATE usuarios 
    SET aprovado = 1, ativo = 1 
    WHERE id = ?
")->execute([$userId]);

// busca role
$stmt = $pdo->prepare("SELECT id FROM roles WHERE nome = ?");
$stmt->execute([$role]);
$roleId = $stmt->fetchColumn();

// vincula role
$pdo->prepare("
    INSERT INTO usuarios_roles (usuario_id, role_id)
    VALUES (?, ?)
")->execute([$userId, $roleId]);

$pdo->commit();

echo json_encode(['status' => 'ok']);
