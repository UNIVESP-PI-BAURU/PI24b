<?php
// Inicie a sessão
session_start();

// Conecte-se ao banco de dados
include 'conexao.php';

// Verifique se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $tipo_usuario = $_POST['tipo_usuario']; // Obter o tipo de usuário selecionado

    // Prepare e execute a consulta dependendo do tipo de usuário
    if ($tipo_usuario === 'aluno') {
        $stmt = $conn->prepare("SELECT * FROM Alunos WHERE email = ? LIMIT 1");
    } else {
        $stmt = $conn->prepare("SELECT * FROM Tutores WHERE email = ? LIMIT 1");
    }
    
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $usuario = $result->fetch_assoc();
        if (password_verify($senha, $usuario['senha'])) {
            // Autenticação bem-sucedida
            $_SESSION['id_usuario'] = $usuario['id']; // O id na tabela correspondente (Alunos ou Tutores)
            $_SESSION['nome'] = $usuario['nome'];
            $_SESSION['tipo_usuario'] = $tipo_usuario; // Salvar tipo de usuário na sessão

            // Redireciona para a página correta com base no tipo de usuário
            if ($tipo_usuario == 'tutor') {
                header("Location: ./TUTORES/dashboard_tutor.php");
            } else {
                header("Location: ./ALUNOS/dashboard_aluno.php");
            }
            exit();
        } else {
            $_SESSION['error'] = "Senha incorreta.";
            header("Location: login.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "Usuário não encontrado.";
        header("Location: login.php");
        exit();
    }
} else {
    $_SESSION['error'] = "Método de requisição inválido.";
    header("Location: login.php");
    exit();
}

// Feche a conexão
$conn->close();
?>
