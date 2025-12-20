<?php
include_once "./config/database.php"; 

$sql = "SELECT id, data_evento, descricao FROM calendario";
$stmt = $pdo->prepare($sql);
$stmt->execute();

$eventos = [];

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $eventos[] = [
        "id" => $row["id"],
        "title" => $row["descricao"],
        "start" => $row["data_evento"]
    ];
}

echo json_encode($eventos);
