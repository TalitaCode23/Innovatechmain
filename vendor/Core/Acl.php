<?php
class Acl {

    private $pdo;
    private $permissoes = [];

    public function __construct($pdo, $role)
    {
        $this->pdo = $pdo;
        $this->carregarPermissoes($role);
    }

    private function carregarPermissoes($role)
    {
        $sql = "
            SELECT p.nome 
            FROM roles_permissoes rp
            JOIN permissoes p ON p.id = rp.permissao_id
            JOIN roles r ON r.id = rp.role_id
            WHERE r.nome = ?
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$role]);

        $this->permissoes = $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function pode($permissao)
    {
        return in_array($permissao, $this->permissoes);
    }
}
