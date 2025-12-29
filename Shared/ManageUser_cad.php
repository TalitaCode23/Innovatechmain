<?php
// ===============================
// CONTROLE DE ACESSO (ACL)
// ===============================
require_once __DIR__ . '/../Includes/acl.php';

/** @var Acl $acl */
if (!$acl->can('GERENCIAR_USUARIOS')) {
    header("Location: ../Shared/acesso-negado.php");
    exit;
}

// ===============================
// CONEXÃO COM BANCO
// ===============================
require_once __DIR__ . '/../Config/database.php';

$db = new Database();
$pdo = $db->getConnection();

// ===============================
// BUSCA USUÁRIOS
// ===============================
$sql = "SELECT id, nome, email, ativo, criado_em FROM usuarios";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include __DIR__ . '/../Includes/Header.php'; ?>

<link rel="stylesheet" href="../Assets/css/Footer.css" />
<link rel="stylesheet" href="../Assets/css/Header.css" />
<link rel="stylesheet" href="../Assets/css/ManageUser.css" />

<div class="container-branco">

    <h1>Gerenciar Usuários - Acesso</h1>

    <p class="descricao">
        ❗ Usuários não são excluídos — apenas ativados ou desativados.
    </p>

    <table class="tabela-usuarios">
        <thead>
            <tr>
                <th>Nome</th>
                <th>E-mail</th>
                <th>Data Solicitação</th>
                <th>Aluno</th>
                <th>Professor(a)</th>
            </tr>
        </thead>

        <tbody>
        <?php if (!empty($usuarios)): ?>
            <?php foreach ($usuarios as $usuario): ?>
                <tr>
                    <td><?= htmlspecialchars($usuario['nome']) ?></td>
                    <td><?= htmlspecialchars($usuario['email']) ?></td>
                    <td><?= date('d/m/Y', strtotime($usuario['criado_em'])) ?></td>

                    <!-- ALUNO -->
                    <td class="center">
                        <label class="switch">
                            <input 
                                type="checkbox"
                                <?= $usuario['ativo'] ? 'checked' : '' ?>
                                onchange="alterarStatus(<?= $usuario['id'] ?>, 'aluno', this.checked)"
                            >
                            <span class="slider"></span>
                        </label>
                    </td>

                    <!-- PROFESSOR -->
                    <td class="center">
                        <label class="switch">
                            <input 
                                type="checkbox"
                                onchange="alterarStatus(<?= $usuario['id'] ?>, 'professor', this.checked)"
                            >
                            <span class="slider"></span>
                        </label>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5" class="center">
                    Nenhum usuário encontrado.
                </td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>

    <div class="acoes">
        <button class="btn-salvar" id="btnSalvar">Salvar</button>
        <a href="../Public/Home.php" class="btn-voltar">Voltar</a>
    </div>

</div>
<div id="toast" class="toast">Permissão salva com sucesso!</div>

<script>
function alterarStatus(usuarioId, tipo, ativo) {
    fetch('../Includes/UpdateUserStatus.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            usuario_id: usuarioId,
            tipo: tipo,
            ativo: ativo ? 1 : 0
        })
    })
    .then(response => response.json())
    .then(data => {
        if (!data.success) {
            alert('Erro ao atualizar acesso do usuário.');
        }
    })
    .catch(() => {
        alert('Erro de comunicação com o servidor.');
    });
}
</script>
<script>
const btnSalvar = document.getElementById('btnSalvar');
const toast = document.getElementById('toast');

btnSalvar.addEventListener('click', function () {
    toast.classList.add('show');

    setTimeout(() => {
        toast.classList.remove('show');
    }, 3000);
});
</script>

<?php include __DIR__ . '/../Includes/Footer.php'; ?>
