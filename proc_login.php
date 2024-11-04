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

    // Debug: Registrar o tipo de usuário e tabela escolhida
    error_log("Tipo de usuário: " . $tipo_usuario);
    error_log("Tabela de usuário: " . $tabela_usuario);

    try {
        // Verifica se o usuário existe no banco de dados
        $sql = "SELECT * FROM $tabela_usuario WHERE email = :email";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        // Debug: Verificar o resultado da consulta
        if ($usuario) {
            error_log("Usuário encontrado: " . print_r($usuario, true));
        } else {
            error_log("Usuário não encontrado para o email: " . $email);
        }

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

                // Debug: Registrar informações de sessão
                error_log("Sessão iniciada com ID: " . $usuario['id']);
                error_log("Nome: " . $usuario['nome']);
                error_log("Tipo de usuário: " . $tipo_usuario);

                // Mensagem de sucesso
                $_SESSION['success'] = "Login realizado com sucesso!";

                // Redireciona para a dashboard correspondente
                if ($tipo_usuario == "aluno") {
                    error_log("Redirecionando para: ./ALUNOS/dashboard_aluno.php");
                    header("Location: ./ALUNOS/dashboard_aluno.php");
                } else {
                    error_log("Redirecionando para: ./TUTORES/dashboard_tutor.php");
                    header("Location: ./TUTORES/dashboard_tutor.php");
                }
                exit();
            } else {
                // Senha incorreta
                $_SESSION['error'] = "Senha incorreta. Por favor, tente novamente.";
                error_log("Senha incorreta para o usuário: " . $email);
                header("Location: login.php");
                exit();
            }
        } else {
            // Usuário não encontrado
            $_SESSION['error'] = "Usuário não encontrado. Por favor, verifique o email e o tipo de usuário.";
            header("Location: login.php");
            exit();
        }
    } catch (PDOException $e) {
        error_log("Erro na consulta SQL: " . $e->getMessage());
        $_SESSION['error'] = "Erro no sistema. Por favor, tente novamente mais tarde.";
        header("Location: login.php");
        exit();
    }
} else {
    // Se não for uma requisição POST válida
    $_SESSION['error'] = "Método de requisição inválido.";
    error_log("Método de requisição inválido.");
    header("Location: login.php");
    exit();
}
?>
