<?php
// ============================================
// Arquivo: conexao_financeiro.php
// Função: Conectar o PHP ao banco de dados financeiro MySQL
// ============================================

$servidor = "localhost";
$usuario  = "root";
$senha    = "";
$banco    = "controle_financeiro";

$conexao = mysqli_connect($servidor, $usuario, $senha, $banco);

if (!$conexao) {
    die("Erro ao conectar com o banco de dados: " . mysqli_connect_error());
}

mysqli_set_charset($conexao, "utf8mb4");
