<?php
session_start();
require 'conexao.php';

$id_usuario = $_SESSION['id_tutor'] ?? $_SESSION['id_aluno'];
$tipo_usuario = isset($_SESSION['id_tutor']) ? 'tutor' : 'aluno';

$id_conversor = $_POST['id'] ?? null;
$tipo_conversor = $_POST['tipo_conversor'] ?? null;
$message = $_POST['message'] ?? null;

if ($message && $id_conversor && $tipo_conversor) {
    // Insere a mensagem no banco de dados
    $stmt = $pdo->prepare("INSERT INTO mensagens (id_remetente, tipo_remetente, id_destinatario, tipo_destinatario, mensagem) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$id_usuario, $tipo_usuario, $id_conversor, $tipo_conversor, $message]);
}
?>
