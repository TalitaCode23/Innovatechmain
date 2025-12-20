<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acesso Negado - Sistema Acadêmico</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #0A0A33, #1a1a4d);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .access-denied-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            padding: 40px;
            max-width: 600px;
            width: 100%;
            text-align: center;
            animation: fadeIn 0.5s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .error-icon {
            font-size: 80px;
            color: #e74c3c;
            margin-bottom: 20px;
            animation: shake 0.5s ease;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }
        
        h1 {
            color: #e74c3c;
            margin-bottom: 15px;
            font-size: 2.2rem;
        }
        
        .message {
            color: #555;
            font-size: 1.1rem;
            line-height: 1.6;
            margin-bottom: 30px;
        }
        
        .user-info-box {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin: 25px 0;
            text-align: left;
            border-left: 4px solid #3498db;
        }
        
        .user-info-box p {
            margin: 10px 0;
            color: #333;
        }
        
        .user-info-box strong {
            color: #0A0A33;
            min-width: 150px;
            display: inline-block;
        }
        
        .role-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: bold;
            margin: 2px;
        }
        
        .role-aluno { background: #28a745; color: white; }
        .role-professor { background: #007bff; color: white; }
        .role-coordenador { background: #ffc107; color: #212529; }
        .role-administrador { background: #dc3545; color: white; }
        
        .action-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 30px;
        }
        
        .btn {
            padding: 12px 25px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.3s ease;
            min-width: 180px;
        }
        
        .btn-primary {
            background: #3498db;
            color: white;
            border: 2px solid #3498db;
        }
        
        .btn-primary:hover {
            background: #2980b9;
            border-color: #2980b9;
            transform: translateY(-2px);
        }
        
        .btn-secondary {
            background: transparent;
            color: #3498db;
            border: 2px solid #3498db;
        }
        
        .btn-secondary:hover {
            background: #3498db;
            color: white;
            transform: translateY(-2px);
        }
        
        .btn-warning {
            background: #f39c12;
            color: white;
            border: 2px solid #f39c12;
        }
        
        .btn-warning:hover {
            background: #d68910;
            border-color: #d68910;
            transform: translateY(-2px);
        }
        
        .contact-info {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #666;
            font-size: 0.9rem;
        }
        
        .contact-info a {
            color: #3498db;
            text-decoration: none;
        }
        
        .contact-info a:hover {
            text-decoration: underline;
        }
        
        @media (max-width: 600px) {
            .access-denied-container {
                padding: 25px;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="access-denied-container">
        <div class="error-icon">
            <i class="fas fa-ban"></i>
        </div>
        
        <h1>Acesso Negado</h1>
        
        <p class="message">
            Você não tem permissão para acessar esta página ou funcionalidade.<br>
        </p>
        
        <div class="user-info-box">
            <p><strong>Usuário:</strong> 
                <?php 
                if (isset($_SESSION['user_nome'])) {
                    echo htmlspecialchars($_SESSION['user_nome']);
                } else {
                    echo '<span style="color: #999;">Não autenticado</span>';
                }
                ?>
            </p>
            
            <p><strong>E-mail:</strong> 
                <?php 
                if (isset($_SESSION['user_email'])) {
                    echo htmlspecialchars($_SESSION['user_email']);
                } else {
                    echo '<span style="color: #999;">Não disponível</span>';
                }
                ?>
            </p>
            
            <p><strong>Papéis:</strong> 
                <?php 
                if (isset($_SESSION['user_roles']) && is_array($_SESSION['user_roles'])) {
                    foreach ($_SESSION['user_roles'] as $role) {
                        $roleClass = strtolower(str_replace(' ', '', $role));
                        echo '<span class="role-badge role-' . $roleClass . '">' . $role . '</span> ';
                    }
                } else {
                    echo '<span style="color: #999;">Nenhum papel atribuído</span>';
                }
                ?>
            </p>
            
            <p><strong>Status:</strong> 
                <?php 
                if (isset($_SESSION['user_aprovado'])) {
                    echo $_SESSION['user_aprovado'] ? 
                         '<span style="color: #28a745;">✓ Aprovado</span>' : 
                         '<span style="color: #f39c12;">⏳ Aguardando aprovação</span>';
                } else {
                    echo '<span style="color: #999;">Desconhecido</span>';
                }
                ?>
            </p>
            
            <p><strong>Página solicitada:</strong> 
                <span style="color: #666;">
                    <?php 
                    echo isset($_SERVER['HTTP_REFERER']) ? 
                         htmlspecialchars(basename($_SERVER['HTTP_REFERER'])) : 
                         'Desconhecida';
                    ?>
                </span>
            </p>
            
            <p><strong>Data/Hora:</strong> 
                <span style="color: #666;">
                    <?php echo date('d/m/Y H:i:s'); ?>
                </span>
            </p>
        </div>
        
        <div class="action-buttons">
            <a href="../Public/Home.php" class="btn btn-primary">
                <i class="fas fa-home"></i> Voltar para Home
            </a>
            
            <?php if (isset($_SESSION['user_roles']) && in_array('Professor', $_SESSION['user_roles']) && !($_SESSION['user_aprovado'] ?? false)): ?>
            <a href="solicitacao_status.php" class="btn btn-warning">
                <i class="fas fa-clock"></i> Ver Status da Solicitação
            </a>
            <?php endif; ?>
            
            <a href="../Shared/Support.php" class="btn btn-secondary">
                <i class="fas fa-question-circle"></i> Solicitar Ajuda
            </a>
        </div>
        
        <div class="contact-info">
            <p>
                <i class="fas fa-info-circle"></i> 
                Se você acredita que deveria ter acesso, entre em contato com o coordenador do curso.
            </p>
            <p>
                <i class="fas fa-shield-alt"></i> 
                Esta tentativa de acesso foi registrada para auditoria de segurança.
            </p>
        </div>
    </div>
    
    <script>
        
        document.addEventListener('DOMContentLoaded', function() {
            
            console.log('Tentativa de acesso negado registrada:', {
                usuario: '<?php echo $_SESSION['user_email'] ?? "Não autenticado"; ?>',
                pagina: '<?php echo $_SERVER['HTTP_REFERER'] ?? "Desconhecida"; ?>',
                data: '<?php echo date('d/m/Y H:i:s'); ?>'
            });
            
            setTimeout(function() {
                window.location.href = '../Public/Home.php';
            }, 60000);
        });
    </script>
</body>
</html>