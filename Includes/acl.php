<?php
session_start();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../vendor/Core/Acl.php';

$db = new Database();
$pdo = $db->getConnection();

// Não logado
if (!isset($_SESSION['user_id'])) {
    header("Location: ../Public/Login.php");
    exit;
}

// Sem roles
if (empty($_SESSION['user_roles'])) {
    echo 'Acesso negado: usuário sem perfil';
    exit;
}

// ACL
$acl = new Acl($pdo, $_SESSION['user_roles']);

// Página atual
$paginaAtual = basename($_SERVER['PHP_SELF']);

// Mapeamento páginas → permissões
$permissoesPorPagina = [
    'AddMembers.php'          => 'GERENCIAR_MEMBROS',
    'DeleteMember.php'        => 'GERENCIAR_MEMBROS',
    'RegisterProject.php'     => 'CRIAR_PROJETO',
    'EditProject.php'         => 'EDITAR_PROJETO',
    'CompleteProject.php'     => 'CONCLUIR_PROJETO',
    'AddTasks.php'            => 'CRIAR_TAREFA',
    'AssignTasks.php'         => 'ATRIBUIR_TAREFAS',
    'EditTasks.php'           => 'EDITAR_TAREFA',
    'CompleteTasks.php'       => 'CONCLUIR_TAREFA',
    'Comments.php'            => 'CRIAR_COMENTARIO',
    'PerformanceReport.php'   => 'VISUALIZAR_RELATORIOS',
    'ProgressReport.php'      => 'VISUALIZAR_RELATORIOS',
    'VisualizarCalendario.php'=> 'VISUALIZAR_CALENDARIO',
    'SystemSettings.php'      => 'VISUALIZAR_CONFIGURACOES',
    'Support.php'             => 'ACESSAR_SUPORTE',
    'ManageUser_cad.php'      => 'GERENCIAR_USUARIOS',
    'ViewTasks.php'           => 'VISUALIZAR_TAREFAS',
    'ViewProjects.php'        => 'VISUALIZAR_PROJETOS',
    'ViewListTasks.php'       => 'VISUALIZAR_TAREFAS',
    'ViewListProjects.php'    => 'VISUALIZAR_PROJETOS',
];

// Verifica se a página tem permissão definida
if (!isset($permissoesPorPagina[$paginaAtual])) {
    echo 'Acesso negado: página sem permissão definida';
    exit;
}

$permissao = $permissoesPorPagina[$paginaAtual];

// Testa permissão
if (!$acl->can($permissao)) {
    echo 'Acesso negado: sem permissão';
    exit;
}
