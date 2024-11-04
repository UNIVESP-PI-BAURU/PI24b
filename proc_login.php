<?php
// Inclui o arquivo de conexão com o banco de dados
require_once 'conexao.php';
session_start(); // Inicia a sessão

// Verifica se foi enviado um formulário de login
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"])) {
    // Recupera os dados do formulário
    $email = htmlspecialchars(trim($_POST["email"])); // Sanitização
    $senha = $_POST["senha"];
    $tipo_usuario = $_POST["tipo_usuario"]; // Tipo de usuário (aluno ou tutor)

    // Define a tabela do banco de dados com base no tipo de usuário
    $tabela_usuario = ($tipo_usuario == "aluno") ? "Alunos" : "Tutores";

    // Verifica se o usuário existe no banco de dados
    $sql = "SELECT * FROM $tabela_usuario WHERE email = :email";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":email", $email);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verifica se o usuário foi encontrado
    if ($usuario) {
        // Verifica a senha usando password_verify
        if (password_verify($senha, $usuario['senha'])) {
            // Armazena o ID e tipo de usuário corretamente na sessão
            if ($tipo_usuario == "aluno") {
                $_SESSION['id_aluno'] = $usuario['id']; // Armazena o ID do aluno
            } else {
                $_SESSION['id_tutor'] = $usuario['id']; // Armazena o ID do tutor
            }

            $_SESSION['nome'] = $usuario['nome']; // Armazena o nome do usuário
            $_SESSION['tipo_usuario'] = $tipo_usuario; // Armazena o tipo de usuário

            // Mensagem de sucesso
            $_SESSION['success'] = "Login realizado com sucesso!";

            // Redireciona para a dashboard correspondente
            if ($tipo_usuario == "aluno") {
                header("Location: ./ALUNOS/dashboard_aluno.php");
            } else {
                header("Location: ./TUTORES/dashboard_tutor.php");
            }
            exit();
        } else {
            // Senha incorreta
            $_SESSION['error'] = "Senha incorreta. Por favor, tente novamente.";
            header("Location: login.php");
            exit();
        }
    } else {
        // Usuário não encontrado
        $_SESSION['error'] = "Usuário não encontrado. Por favor, verifique o email e o tipo de usuário.";
        header("Location: login.php");
        exit();
    }
} else {
    // Se não for uma requisição POST válida
    $_SESSION['error'] = "Método de requisição inválido.";
    header("Location: login.php");
    exit();
}
?>
