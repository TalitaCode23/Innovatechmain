<?php
// includes/auth_functions.php

// ============================================
// CONEXÃO COM O BANCO DE DADOS
// ============================================
function getDBConnection() {
    static $conn = null;
    
    if ($conn === null) {
        $host = 'localhost';
        $username = 'root';
        $password = '';
        $database = 'Innovatechmain';
        
        $conn = new mysqli($host, $username, $password, $database);
        
        if ($conn->connect_error) {
            die("Conexão falhou: " . $conn->connect_error);
        }
        
        $conn->set_charset("utf8mb4");
    }
    
    return $conn;
}

// ============================================
// FUNÇÕES DE AUTENTICAÇÃO
// ============================================

/**
 * Verifica se o usuário está logado
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']) && $_SESSION['user_id'] > 0;
}

/**
 * Redireciona para login se não estiver autenticado
 */
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: ./Public/Login.php');
        exit();
    }
}

/**
 * Obtém o ID do usuário logado
 */
function getUserId() {
    return $_SESSION['user_id'] ?? null;
}

/**
 * Obtém os papéis do usuário logado
 */
function getUserRoles() {
    return $_SESSION['user_roles'] ?? [];
}

/**
 * Verifica se usuário tem um papel específico
 */
function hasRole($role) {
    $roles = getUserRoles();
    return in_array($role, $roles);
}

/**
 * Verifica se usuário tem qualquer um dos papéis especificados
 */
function hasAnyRole($roles) {
    $userRoles = getUserRoles();
    
    foreach ($roles as $role) {
        if (in_array($role, $userRoles)) {
            return true;
        }
    }
    
    return false;
}

/**
 * Verifica se o usuário está aprovado (para professores/coordenadores)
 */
function isApproved() {
    return $_SESSION['user_aprovado'] ?? false;
}

// ============================================
// FUNÇÕES DE PERMISSÃO DE PÁGINAS
// ============================================

/**
 * Configuração das permissões por página
 */
function getPagePermissions() {
    return [
        // PÁGINAS PÚBLICAS (não precisam de login - se houver)
        'Login.php' => ['*'],
        'Register.php' => ['*'],
        'esqueci-senha.php' => ['*'],
        
        // PÁGINAS COMUNS (todos logados)
        'Home.php' => ['Aluno', 'Professor', 'Coordenador', 'Administrador'],
        'perfil.php' => ['Aluno', 'Professor', 'Coordenador', 'Administrador'],
        'Support.php' => ['Aluno', 'Professor', 'Coordenador', 'Administrador'],
        
        // PROJETOS
        'ViewListProject.php' => ['Aluno', 'Professor', 'Coordenador', 'Administrador'],
        'ViewListTasks.php' => ['Aluno', 'Professor', 'Coordenador', 'Administrador'],
        'Calendar.php' => ['Aluno', 'Professor', 'Coordenador', 'Administrador'],
        'Comments.php' => ['Aluno', 'Professor', 'Coordenador', 'Administrador'],
        
        // APENAS PROFESSORES/COORDENADORES/ADMIN (APROVADOS)
        'RegisterProject.php' => ['Professor', 'Coordenador', 'Administrador'],
        'EditProject.php' => ['Professor', 'Coordenador', 'Administrador'],
        'CompleteProject.php' => ['Professor', 'Coordenador', 'Administrador'],
        'AddTasks.php' => ['Professor', 'Coordenador', 'Administrador'],
        'EditTasks.php' => ['Professor', 'Coordenador', 'Administrador'],
        'AddMembers.php' => ['Professor', 'Coordenador', 'Administrador'],
        'AssignTasks.php' => ['Professor', 'Coordenador', 'Administrador'],
        'CompleteTasks.php' => ['Aluno', 'Professor', 'Coordenador', 'Administrador'],
        
        // APENAS COORDENADORES/ADMIN
        'DeleteMember.php' => ['Coordenador', 'Administrador'],
        'ProgressReport.php' => ['Coordenador', 'Administrador'],
        'PerformanceReport.php' => ['Coordenador', 'Administrador'],
        'ManageUser.php' => ['Coordenador', 'Administrador'],
        
        // APENAS ADMIN
        'SystemSettings.php' => ['Administrador'],
        'DeleteTasks.php' => ['Administrador'],
        'admin_config.php' => ['Administrador'],
        'logs.php' => ['Administrador']
    ];
}

/**
 * Verifica se usuário pode acessar uma página específica
 */
function canAccessPage($page_name) {
    $permissions = getPagePermissions();
    
    // Se página não está na lista, nega acesso por segurança
    if (!isset($permissions[$page_name])) {
        return false;
    }
    
    $allowed_roles = $permissions[$page_name];
    
    // Se é página pública (* = todos)
    if (in_array('*', $allowed_roles)) {
        return true;
    }
    
    // Verifica se usuário está logado
    if (!isLoggedIn()) {
        return false;
    }
    
    // Verifica se tem algum dos papéis permitidos
    return hasAnyRole($allowed_roles);
}

/**
 * Verifica permissão e redireciona se necessário
 */
function protectPage($page_name) {
    if (!canAccessPage($page_name)) {
        if (!isLoggedIn()) {
            header('Location: ./Public/Login.php');
        } else {
            echo 'Acesso negado';
        }
        exit();
    }
}

// ============================================
// FUNÇÕES DE PERMISSÃO DE FUNCIONALIDADES
// ============================================

/**
 * Verifica se usuário tem uma permissão específica no banco
 */
function hasPermission($permission_name) {
    if (!isLoggedIn()) {
        return false;
    }
    
    $conn = getDBConnection();
    $user_id = getUserId();
    
    $sql = "SELECT COUNT(*) as count 
            FROM usuarios_roles ur
            JOIN roles_permissoes rp ON ur.role_id = rp.role_id
            JOIN permissoes p ON rp.permissao_id = p.id
            WHERE ur.usuario_id = ? AND p.nome = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $user_id, $permission_name);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    return $row['count'] > 0;
}

/**
 * Obtém todas as permissões do usuário
 */
function getUserPermissions() {
    if (!isLoggedIn()) {
        return [];
    }
    
    $conn = getDBConnection();
    $user_id = getUserId();
    
    $sql = "SELECT p.nome, p.descricao 
            FROM usuarios_roles ur
            JOIN roles_permissoes rp ON ur.role_id = rp.role_id
            JOIN permissoes p ON rp.permissao_id = p.id
            WHERE ur.usuario_id = ?
            GROUP BY p.id";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $permissions = [];
    while ($row = $result->fetch_assoc()) {
        $permissions[] = $row['nome'];
    }
    
    return $permissions;
}

// ============================================
// FUNÇÕES DE VERIFICAÇÃO RÁPIDA
// ============================================

/**
 * Verifica se pode criar projetos
 */
function canCreateProject() {
    return hasAnyRole(['Professor', 'Coordenador', 'Administrador']) && isApproved();
}

/**
 * Verifica se pode editar projetos
 */
function canEditProject() {
    return hasAnyRole(['Professor', 'Coordenador', 'Administrador']) && isApproved();
}

/**
 * Verifica se pode gerenciar usuários
 */
function canManageUsers() {
    return hasAnyRole(['Coordenador', 'Administrador']);
}

/**
 * Verifica se pode excluir conteúdo
 */
function canDeleteContent() {
    return hasAnyRole(['Coordenador', 'Administrador']);
}

/**
 * Verifica se pode ver relatórios
 */
function canViewReports() {
    return hasAnyRole(['Coordenador', 'Administrador']);
}

// ============================================
// FUNÇÕES AUXILIARES
// ============================================

/**
 * Obtém dados do usuário logado
 */
function getCurrentUser() {
    if (!isLoggedIn()) {
        return null;
    }
    
    return [
        'id' => $_SESSION['user_id'] ?? null,
        'nome' => $_SESSION['user_nome'] ?? '',
        'email' => $_SESSION['user_email'] ?? '',
        'roles' => $_SESSION['user_roles'] ?? [],
        'aprovado' => $_SESSION['user_aprovado'] ?? false,
        'tipo_solicitado' => $_SESSION['user_tipo_solicitado'] ?? 'Aluno'
    ];
}

/**
 * Verifica se é coordenador ou admin
 */
function isCoordinatorOrAdmin() {
    return hasAnyRole(['Coordenador', 'Administrador']);
}

/**
 * Verifica se é professor (aprovado)
 */
function isApprovedProfessor() {
    return hasRole('Professor') && isApproved();
}

/**
 * Redireciona alunos para uma página específica
 */
function redirectIfStudent($redirect_to = '../Public/Home.php') {
    if (hasRole('Aluno') && !hasAnyRole(['Professor', 'Coordenador', 'Administrador'])) {
        header("Location: $redirect_to");
        exit();
    }
}

// ============================================
// FUNÇÕES DE LOG E AUDITORIA
// ============================================

/**
 * Registra tentativa de acesso
 */
function logAccessAttempt($page, $success = true) {
    if (!isLoggedIn()) {
        return;
    }
    
    $conn = getDBConnection();
    $user_id = getUserId();
    $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    
    $sql = "INSERT INTO logs_acesso 
            (usuario_id, pagina, ip, user_agent, sucesso, data_hora) 
            VALUES (?, ?, ?, ?, ?, NOW())";
    
    $stmt = $conn->prepare($sql);
    
    // VERIFICAÇÃO CRÍTICA ADICIONADA
    if (!$stmt) {
        error_log("Erro ao preparar query de log: " . $conn->error);
        return;
    }
    
    $stmt->bind_param("isssi", $user_id, $page, $ip, $user_agent, $success);
    
    if (!$stmt->execute()) {
        error_log("Erro ao executar log: " . $stmt->error);
    }
    
    $stmt->close();
}

// ============================================
// INICIALIZAÇÃO DA SESSÃO
// ============================================

/**
 * Inicializa a sessão com segurança
 */
function startSecureSession() {
    if (session_status() === PHP_SESSION_NONE) {
        // Configurações de segurança da sessão
        ini_set('session.cookie_httponly', 1);
        ini_set('session.use_only_cookies', 1);
        ini_set('session.cookie_secure', isset($_SERVER['HTTPS']));
        
        session_start();
        
        // Regenera ID da sessão periodicamente para segurança
        if (!isset($_SESSION['created'])) {
            $_SESSION['created'] = time();
        } else if (time() - $_SESSION['created'] > 1800) {
            // Regenera a cada 30 minutos
            session_regenerate_id(true);
            $_SESSION['created'] = time();
        }
    }
}

// ============================================
// FUNÇÃO PRINCIPAL DE PROTEÇÃO
// ============================================

/**
 * Protege a página atual automaticamente
 * Use esta função no início de cada página
 */
function protectCurrentPage() {
    startSecureSession();
    
    $current_page = basename($_SERVER['PHP_SELF']);
    
    // Páginas que não precisam de autenticação
    $public_pages = ['Login.php', 'Register.php', 'esqueci-senha.php'];
    
    if (in_array($current_page, $public_pages)) {
        return; // Página pública, não verifica
    }
    
    // Verifica se está logado
    if (!isLoggedIn()) {
        $_SESSION['redirect_to'] = $_SERVER['REQUEST_URI'];
        
        // CORREÇÃO: Caminho relativo CORRETO
        // Depende de onde a página está chamando
        
        // Se a página está em Public/, redireciona para Login.php
        // Se a página está em outra pasta, redireciona para ../Public/Login.php
        $current_dir = dirname($_SERVER['PHP_SELF']);
        
        if (strpos($current_dir, 'Public') !== false) {
            // Já está na pasta Public
            header('Location: Login.php');
        } else {
            // Precisa voltar para Public
            header('Location: ../Public/Login.php');
        }
        
        exit();
    }
    
    // Verifica permissão para a página
    if (!canAccessPage($current_page)) {
        logAccessAttempt($current_page, false);
        header('Location: ../Shared/acesso-negado.php');
        exit();
    }
    
    // Log de acesso bem-sucedido
    logAccessAttempt($current_page, true);
}

?>