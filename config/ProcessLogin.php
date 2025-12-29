<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../Public/Login.php");
    exit;
}

$email = trim($_POST['email']);
$senha = $_POST['password'];

// Buscar usuário
$sql = "SELECT id, nome, email, senha, tipo_solicitado, aprovado, ativo
        FROM usuarios
        WHERE email = ? LIMIT 1";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    header("Location: ../Public/Login.php?error=invalid_credentials");
    exit;
}

$user = $result->fetch_assoc();

// Conta inativa
if ($user['ativo'] != 1) {
    header("Location: ../Public/Login.php?error=inactive");
    exit;
}

// Conta não aprovada
if ($user['aprovado'] != 1) {
    header("Location: ../Public/Login.php?error=not_approved");
    exit;
}

// Verificar senha
if (!password_verify($senha, $user['senha'])) {
    header("Location: ../Public/Login.php?error=invalid_credentials");
    exit;
}

/* =========================================
   BUSCAR ROLES DO USUÁRIO
========================================= */

$sql = "
    SELECT DISTINCT r.nome
    FROM usuarios_roles ur
    JOIN roles r ON r.id = ur.role_id
    WHERE ur.usuario_id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user['id']);
$stmt->execute();
$result = $stmt->get_result();

$roles = [];
while ($row = $result->fetch_assoc()) {
    $roles[] = $row['nome'];
}

/* =========================================
   LOGIN OK - SETAR SESSÃO
========================================= */

$_SESSION['user_id'] = $user['id'];
$_SESSION['user_nome'] = $user['nome'];
$_SESSION['user_email'] = $user['email'];
$_SESSION['user_roles'] = $roles;
$_SESSION['user_aprovado'] = (bool)$user['aprovado'];
$_SESSION['user_tipo_solicitado'] = $roles[0] ?? 'Aluno';

/* =========================================
   REDIRECIONAMENTO
========================================= */

header("Location: ../Public/Home.php");
exit;
