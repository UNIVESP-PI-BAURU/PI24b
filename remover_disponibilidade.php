<?php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo_usuario'] !== 'tutor') {
    header("Location: login.php");
    exit;
}

require_once('conexao.php'); // Conexão com o banco de dados

// Verifica se o id foi passado na URL
if (isset($_GET['id'])) {
    $id_disponibilidade = $_GET['id'];

    // Remove a disponibilidade da tabela
    $sql = "DELETE FROM Disponibilidade_Tutores WHERE id = ? AND id_tutor = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $id_disponibilidade, $_SESSION['id_usuario']);
    $stmt->execute();

    // Mensagem de sucesso
    $_SESSION['msg'] = "Disponibilidade removida com sucesso!";
    header("Location: editar_disponibilidade.php");
    exit;
} else {
    // Mensagem de erro
    $_SESSION['msg'] = "Erro: ID da disponibilidade não especificado!";
    header("Location: editar_disponibilidade.php");
    exit;
}
?>
