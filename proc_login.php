<?php
// Inclui o arquivo de conexão com o banco de dados
require_once 'conexao.php';
session_start(); // Inicia a sessão

// Verifica se foi enviado um formulário de login
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"])) {
    // Recupera e sanitiza os dados do formulário
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $senha = $_POST["senha"];
    $tipo_usuario = $_POST["tipo_usuario"];

    // Verifica se o tipo de usuário é válido
    if ($tipo_usuario !== "aluno" && $tipo_usuario !== "tutor") {
        $_SESSION['error'] = "Tipo de usuário inválido.";
        error_log("Tipo de usuário inválido: " . htmlspecialchars($tipo_usuario));
        header("Location: login.php");
        exit();
    }

    // Define a tabela do banco de dados com base no tipo de usuário
    $tabela_usuario = ($tipo_usuario === "aluno") ? "Alunos" : "Tutores";

    // Debug: Registrar o tipo de usuário e tabela escolhida
    error_log("Tipo de usuário: " . $tipo_usuario);
    error_log("Tabela de usuário: " . $tabela_usuario);

    try {
        // Prepara a consulta para verificar se o usuário existe no banco de dados
        $sql = "SELECT * FROM $tabela_usuario WHERE email = :email";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        // Debug: Verificar o resultado da consulta
        error_log("Resultado da consulta para o email {$email}: " . ($usuario ? "Usuário encontrado" : "Usuário não encontrado"));

        // Verifica se o usuário foi encontrado
        if ($usuario) {
            // Verifica a senha usando password_verify
            if (password_verify($senha, $usuario['senha'])) {
                // Armazena o ID e tipo de usuário corretamente na sessão
                $session_key = ($tipo_usuario === "aluno") ? 'id_aluno' : 'id_tutor';
                $_SESSION[$session_key] = $usuario['id'];
                $_SESSION['nome'] = $usuario['nome'];
                $_SESSION['tipo_usuario'] = $tipo_usuario;

                // Debug: Registrar informações de sessão
                error_log("Sessão iniciada com ID: " . $usuario['id']);
                error_log("Nome: " . $usuario['nome']);
                error_log("Tipo de usuário: " . $tipo_usuario);

                // Mensagem de sucesso
                $_SESSION['success'] = "Login realizado com sucesso!";

                // Redireciona para a dashboard correspondente
                $redirect_url = ($tipo_usuario === "aluno") ? './ALUNOS/dashboard_aluno.php' : './TUTORES/dashboard_tutor.php';
                error_log("Redirecionando para: " . $redirect_url);
                header("Location: $redirect_url");
                exit();
            } else {
                // Senha incorreta
                $_SESSION['error'] = "Senha incorreta. Por favor, tente novamente.";
                error_log("Senha incorreta para o usuário: " . $email);
            }
        } else {
            // Usuário não encontrado
            $_SESSION['error'] = "Usuário não encontrado. Verifique o email e o tipo de usuário.";
        }
    } catch (PDOException $e) {
        // Log de erro de consulta SQL
        error_log("Erro na consulta SQL: " . $e->getMessage());
        $_SESSION['error'] = "Erro no sistema. Por favor, tente novamente mais tarde.";
    }

    // Redireciona de volta ao login após qualquer erro
    header("Location: login.php");
    exit();
} else {
    // Se não for uma requisição POST válida
    $_SESSION['error'] = "Método de requisição inválido.";
    error_log("Método de requisição inválido.");
    header("Location: login.php");
    exit();
}
?>
