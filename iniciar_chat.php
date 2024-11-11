<?php
session_start();
require_once 'conexao.php';

if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo_usuario'] !== 'aluno') {
    header("Location: login.php");
    exit();
}

$id_aluno = $_SESSION['id_usuario'];
$id_tutor = isset($_POST['id_destinatario']) ? intval($_POST['id_destinatario']) : null;

if (!$id_tutor) {
    header("Location: dashboard.php");
    exit();
}

// Verifica se a conversa já existe
$sql_verifica = "SELECT id_conversa FROM Conversas WHERE id_aluno = :id_aluno AND id_tutor = :id_tutor";
$stmt_verifica = $conn->prepare($sql_verifica);
$stmt_verifica->bindParam(':id_aluno', $id_aluno, PDO::PARAM_INT);
$stmt_verifica->bindParam(':id_tutor', $id_tutor, PDO::PARAM_INT);
$stmt_verifica->execute();
$conversa = $stmt_verifica->fetch(PDO::FETCH_ASSOC);

if (!$conversa) {
    // Se não existir conversa, cria uma nova
    $sql_inicia_conversa = "INSERT INTO Conversas (id_aluno, id_tutor) VALUES (:id_aluno, :id_tutor)";
    $stmt_inicia_conversa = $conn->prepare($sql_inicia_conversa);
    $stmt_inicia_conversa->bindParam(':id_aluno', $id_aluno, PDO::PARAM_INT);
    $stmt_inicia_conversa->bindParam(':id_tutor', $id_tutor, PDO::PARAM_INT);
    $stmt_inicia_conversa->execute();

    $id_conversa = $conn->lastInsertId();
} else {
    $id_conversa = $conversa['id_conversa'];
}

// Redireciona para conversa.php com o id_conversa
header("Location: conversa.php?id_conversa=$id_conversa");
exit();
?>
