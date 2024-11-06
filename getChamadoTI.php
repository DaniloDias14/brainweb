<?php 
include 'lconfig.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $conexao->prepare("SELECT * FROM chamados INNER JOIN usuarios ON (usuarios.id = chamados.usuario_responsavel) WHERE descricao like '%?%';");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();

    $result = $stmt->get_result();
    $data = $result->fetch_all(MYSQLI_ASSOC);

    echo json_encode(['success' => true, 'message' => '' , 'data' => $data]);

    $stmt->close();
    $conexao->close();
}
?>
