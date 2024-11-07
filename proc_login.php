<?php
session_start(); // Inicia a sessão

// Inclua a conexão com o banco
require_once 'conexao.php';

// Obtenha os dados do formulário
$email = $_POST['email'];
$senha = $_POST['senha'];
$tipo_usuario = $_POST['tipo_usuario']; // Pode ser 'aluno' ou 'tutor'

// Verifica qual tabela consultar, dependendo do tipo de usuário
if ($tipo_usuario === 'aluno') {
    $query = "SELECT id, senha FROM Alunos WHERE email = :email";
} else {
    $query = "SELECT id, senha FROM Tutores WHERE email = :email";
}

$stmt = $conn->prepare($query);
$stmt->bindParam(':email', $email);

// Verifica se a execução da consulta foi bem-sucedida
if ($stmt->execute()) {
    // Verifique se encontrou o usuário
    if ($stmt->rowCount() > 0) {
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verifica se a senha é válida
        if (password_verify($senha, $usuario['senha'])) {
            // Configura as variáveis de sessão
            $_SESSION['id'] = $usuario['id']; // ID do usuário
            $_SESSION['tipo'] = $tipo_usuario; // Tipo de usuário (aluno ou tutor)

            // Redireciona para a página de dashboard
            header("Location: dashboard.php");
            exit(); // Evita que o código após isso seja executado
        } else {
            // Senha incorreta
            $_SESSION['login_error'] = 'Senha incorreta.';
            header("Location: login.php");
            exit();
        }
    } else {
        // Usuário não encontrado
        $_SESSION['login_error'] = 'Email ou senha incorretos.';
        header("Location: login.php");
        exit();
    }
} else {
    // Erro na execução da consulta
    error_log("Erro ao executar consulta de login: " . $stmt->errorInfo()[2]);
    $_SESSION['login_error'] = 'Erro ao processar a solicitação. Tente novamente.';
    header("Location: login.php");
    exit();
}

$conn = null;
?>
