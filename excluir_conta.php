<?php
session_start();
require_once '../conexao.php';

// Verifica se o usuário está logado e qual tipo de usuário
if (!isset($_SESSION['id']) || !isset($_SESSION['tipo'])) {
    header("Location: ../login.php");
    exit();
}

// Recupera o ID e tipo de usuário
$id_usuario = $_SESSION['id'];
$tipo_usuario = $_SESSION['tipo']; // 'aluno' ou 'tutor'

// Define a tabela correta de idiomas com base no tipo de usuário
$tabela_usuario = ($tipo_usuario === 'aluno') ? 'Alunos' : 'Tutores';

// Deleta o usuário da tabela correspondente
$sql = "DELETE FROM $tabela_usuario WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $id_usuario);
$stmt->execute();

// Deleta os idiomas associados ao usuário (tabela Idioma)
$sql_delete_idiomas = "DELETE FROM Idioma WHERE id_usuario = :id_usuario";
$stmt_delete_idiomas = $conn->prepare($sql_delete_idiomas);
$stmt_delete_idiomas->bindParam(':id_usuario', $id_usuario);
$stmt_delete_idiomas->execute();

// Encerra a sessão e redireciona para a página de login
session_destroy();
header("Location: ../login.php");
exit();
?>
