<?php

$dbHost = 'localhost';
$dbUsername = 'root';
$dbPassword = 'Dd5834fg5576!'; // Use sua senha correta aqui
$dbName = 'login';

// Criando a conexão
$conexao = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

// Verificando a conexão
if ($conexao->connect_errno) {
    die("Falha ao conectar ao MySQL: " . $conexao->connect_error);
}

?>
