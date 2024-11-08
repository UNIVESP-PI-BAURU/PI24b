<?php
session_start();
require_once 'conexao.php';

if (!isset($_SESSION['id_usuario']) || !isset($_GET['id'])) {
    header("Location: login.php");
    exit();
}

$id_usuario_logado = $_SESSION['id_usuario'];
$id_destinatario = $_GET['id'];

// Verifica se a conversa já existe
$sql = "SELECT id_conversa FROM Conversas WHERE (id_usuario1 = :user1 AND id_usuario2 = :user2) OR (id_usuario1 = :user2 AND id_usuario2 = :user1)";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':user1', $id_usuario_logado);
$stmt->bindParam(':user2', $id_destinatario);
$stmt->execute();
$conversa = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$conversa) {
    // Se não existir, cria uma nova conversa
    $sql_insert = "INSERT INTO Conversas (id_usuario1, id_usuario2) VALUES (:user1, :user2)";
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bindParam(':user1', $id_usuario_logado);
    $stmt_insert->bindParam(':user2', $id_destinatario);
    $stmt_insert->execute();
    $id_conversa = $conn->lastInsertId();
} else {
    $id_conversa = $conversa['id_conversa'];
}

header("Location: conversa.php?id_conversa=$id_conversa");
exit();
