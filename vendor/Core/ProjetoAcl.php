<?php
class ProjetoAcl {

    public static function usuarioNoProjeto($pdo, $usuarioId, $projetoId)
    {
        $sql = "SELECT 1 FROM projetos_membros 
                WHERE usuario_id = ? AND projeto_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$usuarioId, $projetoId]);

        return $stmt->fetchColumn();
    }

    public static function podeGerenciar($pdo, $usuarioId, $projetoId)
    {
        $sql = "
            SELECT 1 FROM projetos_membros 
            WHERE usuario_id = ? 
              AND projeto_id = ?
              AND funcao IN ('Professor','Coordenador')
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$usuarioId, $projetoId]);

        return $stmt->fetchColumn();
    }
}
