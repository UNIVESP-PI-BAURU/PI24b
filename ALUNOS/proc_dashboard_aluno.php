<?php
session_start(); // Inicia a sessão

// Verifica se o aluno está logado e redireciona para login se não estiver
if (!isset($_SESSION['id_aluno'])) {
    header("Location: ../login.php");
    exit();
}

require_once '../conexao.php'; // Inclui a conexão com o banco

$id_usuario = $_SESSION['id_aluno'];
$tabela_usuario = 'Alunos';

// Consulta os dados do aluno
$sql = "SELECT nome, foto_perfil, cidade, estado FROM $tabela_usuario WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $id_usuario);
$stmt->execute();
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

// Finaliza a execução se o aluno não for encontrado
if (!$usuario) {
    header("Location: ../login.php");
    exit();
}

// O array $usuario agora está disponível para uso na dashboard de aluno
?>
