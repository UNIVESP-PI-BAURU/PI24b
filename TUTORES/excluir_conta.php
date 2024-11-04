<?php
require_once '../conexao.php';

// Início da sessão e verificação de autenticação
session_start();
if (!isset($_SESSION['id_aluno']) && !isset($_SESSION['id_tutor'])) {
    header("Location: ../login.php");
    exit();
}

$id_usuario = isset($_SESSION['id_aluno']) ? $_SESSION['id_aluno'] : $_SESSION['id_tutor'];
$tabela_usuario = isset($_SESSION['id_aluno']) ? 'Alunos' : 'Tutores';

$sql = "DELETE FROM $tabela_usuario WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $id_usuario);
$stmt->execute();

session_destroy();
header("Location: ../login.php");
exit();
?>
