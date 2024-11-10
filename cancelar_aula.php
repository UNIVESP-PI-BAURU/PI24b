<?php
// Conexão com o banco de dados
require_once 'conexao.php';

// Inicialização da sessão
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo_usuario'] !== 'aluno') {
    echo "Usuário não autorizado! Redirecionando para login...";
    header("Location: login.php");
    exit();
}

// Verifica se o ID da aula foi enviado
if (isset($_POST['id_aula'])) {
    $id_aula = $_POST['id_aula'];

    // Deletar o agendamento
    $sql_cancelamento = "DELETE FROM Aulas WHERE id_aula = ? AND id_usuario = ?";
    $stmt = $conn->prepare($sql_cancelamento);
    $stmt->bind_param("ii", $id_aula, $_SESSION['id_usuario']);
    $stmt->execute();

    // Mensagem de sucesso
    $_SESSION['msg'] = "Aula cancelada com sucesso!";
    header("Location: agendamentos.php");
    exit();
} else {
    // Mensagem de erro
    $_SESSION['msg'] = "Erro: ID da aula não encontrado!";
    header("Location: agendamentos.php");
    exit();
}
