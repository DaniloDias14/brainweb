<?php 
include 'lconfig.php';
global $_SESSION;
$_SESSION = [];
// session_start(); 
// if (!isset($_SESSION['user'])) {
//     header("Location: login.php");
//     exit();
// }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conexao->prepare("SELECT * FROM usuarios WHERE login = ? AND senha = ?");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();

    $result = $stmt->get_result();
    $data = $result->fetch_all(MYSQLI_ASSOC);

    if ($result->num_rows > 0) {
        $_SESSION['user'] = $data;
        echo json_encode(['success' => true, 'message' => 'Login efetuado com sucesso' , 'data' => $data]);
    } else {
        echo json_encode(['success' => false, 'message' => 'E-mail ou senha incorretos']);
    }

    $stmt->close();
    $conexao->close();
}
?>
