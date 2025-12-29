<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../Config/database.php';

$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    echo json_encode([
        'success' => false,
        'message' => 'JSON inválido'
    ]);
    exit;
}

$usuarioId = $data['usuario_id'] ?? null;
$ativo     = $data['ativo'] ?? null;

if (!$usuarioId || !isset($ativo)) {
    echo json_encode([
        'success' => false,
        'message' => 'Dados incompletos'
    ]);
    exit;
}

try {
    $db = new Database();
    $pdo = $db->getConnection();

    $sql = "UPDATE usuarios SET ativo = :ativo WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':ativo', (int)$ativo, PDO::PARAM_INT);
    $stmt->bindValue(':id', (int)$usuarioId, PDO::PARAM_INT);
    $stmt->execute();

    echo json_encode([
        'success' => true
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
