<?php
require_once '../conexao.php';
require_once '../session_control.php'; // Inclui o controle de sessão

// Verifica se o usuário está logado e define a tabela
if (isset($_SESSION['id_aluno'])) {
    $id_usuario = $_SESSION['id_aluno'];
    $tabela_usuario = 'Alunos';
} elseif (isset($_SESSION['id_tutor'])) {
    $id_usuario = $_SESSION['id_tutor'];
    $tabela_usuario = 'Tutores';
} else {
    header("Location: ../login.php");
    exit();
}

// Deleta o usuário do banco de dados
$sql = "DELETE FROM $tabela_usuario WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $id_usuario);
$stmt->execute();

// Encerra a sessão e redireciona para a página de login
session_destroy();
header("Location: ../login.php");
exit();
?>
