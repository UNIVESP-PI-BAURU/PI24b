<?php
session_start();
require_once 'conexao.php';

// Verifica se os campos foram enviados
$email = isset($_POST['email']) ? $_POST['email'] : '';
$senha = isset($_POST['senha']) ? $_POST['senha'] : '';
$tipo_usuario = isset($_POST['tipo_usuario']) ? $_POST['tipo_usuario'] : '';

if (empty($email) || empty($senha) || empty($tipo_usuario)) {
    $_SESSION['erro_login'] = 'Por favor, preencha todos os campos.';
    header("Location: login.php");
    exit();
}

try {
    // Consulta SQL com base no tipo de usuário
    $tabela = ($tipo_usuario === 'aluno') ? 'Alunos' : 'Tutores';
    $sql = "SELECT id, senha FROM $tabela WHERE email = :email";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($senha, $usuario['senha'])) {
        $_SESSION['id'] = $usuario['id'];
        $_SESSION['tipo'] = $tipo_usuario;
        header("Location: dashboard.php");
        exit();
    } else {
        $_SESSION['erro_login'] = 'Email ou senha incorretos.';
        header("Location: login.php");
        exit();
    }
} catch (PDOException $e) {
    error_log("Erro de login: " . $e->getMessage());
    $_SESSION['erro_login'] = 'Erro ao tentar fazer login.';
    header("Location: login.php");
    exit();
}
?>
