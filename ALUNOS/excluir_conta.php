<?php
session_start();
require_once '../conexao.php';

if (isset($_SESSION['id_aluno'])) {
    $id_usuario = $_SESSION['id_aluno'];
    $tabela_usuario = 'Alunos';
} else {
    $id_usuario = $_SESSION['id_tutor'];
    $tabela_usuario = 'Tutores';
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
