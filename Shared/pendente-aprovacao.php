<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aprovação Pendente - Sistema Acadêmico</title>
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
        
        .pending-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            padding: 40px;
            max-width: 700px;
            width: 100%;
            text-align: center;
            animation: fadeIn 0.5s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .pending-icon {
            font-size: 80px;
            color: #f39c12;
            margin-bottom: 20px;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        
        h1 {
            color: #f39c12;
            margin-bottom: 15px;
            font-size: 2.2rem;
        }
        
        .subtitle {
            color: #555;
            font-size: 1.2rem;
            margin-bottom: 10px;
            font-weight: bold;
        }
        
        .message {
            color: #666;
            font-size: 1.1rem;
            line-height: 1.6;
            margin-bottom: 25px;
        }
        
        .info-box {
            background: #fff9e6;
            border: 2px dashed #f39c12;
            border-radius: 10px;
            padding: 25px;
            margin: 25px 0;
            text-align: left;
        }
        
        .info-box h3 {
            color: #f39c12;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .info-item {
            display: flex;
            justify-content: space-between;
            margin: 12px 0;
            padding-bottom: 12px;
            border-bottom: 1px solid #ffeaa7;
        }
        
        .info-label {
            font-weight: bold;
            color: #0A0A33;
            min-width: 200px;
        }
        
        .info-value {
            color: #333;
            flex: 1;
            text-align: right;
        }
        
        .features-list {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
            text-align: left;
        }
        
        .features-list h3 {
            color: #0A0A33;
            margin-bottom: 15px;
        }
        
        .features-list ul {
            list-style: none;
            padding-left: 0;
        }
        
        .features-list li {
            padding: 8px 0;
            display: flex;
            align-items: center;
            gap: 10px;
            color: #555;
        }
        
        .features-list li i {
            color: #3498db;
        }
        
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
            min-width: 200px;
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
        
        .btn-light {
            background: transparent;
            color: #0A0A33;
            border: 2px solid #ddd;
        }
        
        .btn-light:hover {
            background: #f8f9fa;
            border-color: #ccc;
            transform: translateY(-2px);
        }
        
        .contact-section {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        
        .contact-section h3 {
            color: #0A0A33;
            margin-bottom: 15px;
        }
        
        .contact-info {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }
        
        .contact-item {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            min-width: 200px;
        }
        
        .contact-item i {
            color: #3498db;
            margin-right: 8px;
        }
        
        @media (max-width: 768px) {
            .pending-container {
                padding: 25px;
            }
            
            .info-item {
                flex-direction: column;
                gap: 5px;
            }
            
            .info-label {
                min-width: auto;
            }
            
            .info-value {
                text-align: left;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
            }
            
            .contact-info {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="pending-container">
        <div class="pending-icon">
            <i class="fas fa-clock"></i>
        </div>
        
        <h1>Aprovação Pendente</h1>
        
        <p class="subtitle">
            Sua solicitação para ser 
            <?php 
            echo isset($_SESSION['user_tipo_solicitado']) ? 
                 htmlspecialchars($_SESSION['user_tipo_solicitado']) : 
                 'Professor/Coordenador';
            ?>
            está em análise
        </p>
        
        <p class="message">
            Aguarde a aprovação do coordenador para acessar todas as funcionalidades do sistema.<br>
            Enquanto isso, você pode utilizar as funcionalidades básicas disponíveis.
        </p>
        
        <div class="info-box">
            <h3><i class="fas fa-info-circle"></i> Informações da sua solicitação</h3>
            
            <div class="info-item">
                <span class="info-label">Nome completo:</span>
                <span class="info-value">
                    <?php 
                    echo isset($_SESSION['user_nome']) ? 
                         htmlspecialchars($_SESSION['user_nome']) : 
                         'Não disponível';
                    ?>
                </span>
            </div>
            
            <div class="info-item">
                <span class="info-label">E-mail:</span>
                <span class="info-value">
                    <?php 
                    echo isset($_SESSION['user_email']) ? 
                         htmlspecialchars($_SESSION['user_email']) : 
                         'Não disponível';
                    ?>
                </span>
            </div>
            
            <div class="info-item">
                <span class="info-label">Cargo solicitado:</span>
                <span class="info-value">
                    <span style="
                        background: #f39c12;
                        color: white;
                        padding: 4px 12px;
                        border-radius: 20px;
                        font-weight: bold;
                    ">
                        <?php 
                        echo isset($_SESSION['user_tipo_solicitado']) ? 
                             htmlspecialchars($_SESSION['user_tipo_solicitado']) : 
                             'Professor';
                        ?>
                    </span>
                </span>
            </div>
            
            <div class="info-item">
                <span class="info-label">Data da solicitação:</span>
                <span class="info-value">
                    <?php 
                    echo isset($_SESSION['data_solicitacao']) ? 
                         htmlspecialchars($_SESSION['data_solicitacao']) : 
                         date('d/m/Y');
                    ?>
                </span>
            </div>
            
            <div class="info-item">
                <span class="info-label">Status atual:</span>
                <span class="info-value">
                    <span style="
                        background: #fff3cd;
                        color: #856404;
                        padding: 4px 12px;
                        border-radius: 20px;
                        font-weight: bold;
                        border: 1px solid #ffeaa7;
                    ">
                        <i class="fas fa-clock"></i> Aguardando aprovação
                    </span>
                </span>
            </div>
        </div>
        
        <div class="features-list">
            <h3>O que você poderá fazer após a aprovação:</h3>
            <ul>
                <li><i class="fas fa-plus-circle"></i> Criar novos projetos acadêmicos</li>
                <li><i class="fas fa-edit"></i> Editar e gerenciar projetos existentes</li>
                <li><i class="fas fa-tasks"></i> Adicionar e atribuir tarefas</li>
                <li><i class="fas fa-users"></i> Gerenciar membros dos projetos</li>
                <li><i class="fas fa-chart-bar"></i> Gerar relatórios de progresso</li>
                <li><i class="fas fa-calendar-check"></i> Concluir projetos e tarefas</li>
            </ul>
        </div>
        
        <div class="action-buttons">
            <a href="../Public/Home.php" class="btn btn-primary">
                <i class="fas fa-home"></i> Acessar Dashboard
            </a>
            
            <a href="../Shared/Support.php" class="btn btn-warning">
                <i class="fas fa-question-circle"></i> Ajuda e Suporte
            </a>
            
            <a href="logout.php?reason=pending" class="btn btn-light">
                <i class="fas fa-sign-out-alt"></i> Sair do Sistema
            </a>
        </div>
        
        <div class="contact-section">
            <h3>Precisa de ajuda ou mais informações?</h3>
            <div class="contact-info">
                <div class="contact-item">
                    <i class="fas fa-user-tie"></i>
                    <strong>Coordenador:</strong> Prof. Responsável
                </div>
                <div class="contact-item">
                    <i class="fas fa-envelope"></i>
                    <strong>E-mail:</strong> coordenacao@instituicao.edu.br
                </div>
                <div class="contact-item">
                    <i class="fas fa-phone"></i>
                    <strong>Telefone:</strong> (11) 99999-9999
                </div>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function checkApprovalStatus() {
                console.log('Verificando status de aprovação...');
                
            }
            
            setInterval(checkApprovalStatus, 300000);
            
            const waitTime = document.createElement('div');
            waitTime.innerHTML = `
                <div style="
                    margin-top: 20px;
                    padding: 15px;
                    background: #e8f4fd;
                    border-radius: 8px;
                    color: #0c5460;
                    border-left: 4px solid #3498db;
                ">
                    <i class="fas fa-info-circle"></i>
                    <strong>Tempo estimado:</strong> Aprovações são processadas em até 48 horas úteis.
                </div>
            `;
            document.querySelector('.info-box').appendChild(waitTime);
        });
    </script>
</body>
</html>