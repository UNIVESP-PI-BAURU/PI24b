<?php
session_start();
require_once './conexao.php';

// Verifica se o usuário está logado e qual tipo de usuário
if (!isset($_SESSION['id']) || !isset($_SESSION['tipo'])) {
    header("Location: ../login.php");
    exit();
}

// Recupera o ID e tipo de usuário
$id_usuario = $_SESSION['id'];
$tipo_usuario = $_SESSION['tipo']; // 'aluno' ou 'tutor'

// Define a tabela correta conforme o tipo de usuário
$tabela_usuario = ($tipo_usuario === 'aluno') ? 'Alunos' : 'Tutores';

// Deleta o usuário da tabela
$sql = "DELETE FROM $tabela_usuario WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $id_usuario, PDO::PARAM_INT);

if ($stmt->execute()) {
    session_destroy();
    header("Location: ../login.php");
    exit();
} else {
    die("Erro ao excluir a conta.");
}
?>
