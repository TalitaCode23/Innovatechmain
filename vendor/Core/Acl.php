<?php

class Acl {
    private PDO $pdo;
    private array $roles;

    public function __construct(PDO $pdo, array $roles) {
        $this->pdo = $pdo;
        $this->roles = $roles;
    }

    public function can(string $permissao): bool {
        $placeholders = implode(',', array_fill(0, count($this->roles), '?'));

        $sql = "
            SELECT COUNT(*)
            FROM permissoes p
            JOIN roles_permissoes rp ON rp.permissao_id = p.id
            JOIN roles r ON r.id = rp.role_id
            WHERE p.nome = ?
            AND r.nome IN ($placeholders)
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array_merge([$permissao], $this->roles));

        return $stmt->fetchColumn() > 0;
    }

}
