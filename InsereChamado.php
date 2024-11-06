<?php
include 'lconfig.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $anexo = $_POST['img'];
    $desc = $_POST['descricao'];

    $query = "INSERT INTO chamados (id, incidente, descricao, anexo, usuario_responsavel, comentario)
                VALUES (NULL, 'teste', ?, ?, 1, 'teste')";

    $stmt = $conexao->prepare($query);
    $stmt->bind_param("ss", $desc, $anexo);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Registro efetuado com sucesso'], true);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao registrar usuário'], true);
    }

    $stmt->close();
    $conexao->close();
}

?>