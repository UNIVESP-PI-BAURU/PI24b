<?php
session_start(); // Inicia a sessão

// Inclua a conexão com o banco
require_once 'conexao.php';

// Obtenha os dados do formulário
$email = $_POST['email'];
$senha = $_POST['senha'];

// Verifica se o tipo de usuário é aluno ou tutor
$tipo_usuario = $_POST['tipo_usuario'];

// Consulta SQL para verificar o usuário
if ($tipo_usuario === 'aluno') {
    $query = "SELECT id, senha FROM Alunos WHERE email = :email";
} else {
    $query = "SELECT id, senha FROM Tutores WHERE email = :email";
}

$stmt = $conn->prepare($query);
$stmt->bindParam(':email', $email);
$stmt->execute();

// Verifique se encontrou o usuário na tabela
if ($stmt->rowCount() > 0) {
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verifica se a senha é válida
    if (password_verify($senha, $usuario['senha'])) {
        $_SESSION['id'] = $usuario['id'];
        $_SESSION['tipo'] = $tipo_usuario;

        // Redireciona para a página de dashboard
        header("Location: dashboard.php");
        exit();
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
?>
