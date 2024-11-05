<?php
session_start();
require_once 'conexao.php'; // Inclui a conexão com o banco

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT); // Hash da senha
    $tipo_usuario = $_POST['tipo_usuario']; // Obter o tipo de usuário selecionado
    $nome = $_POST['nome']; // Obter o nome do usuário

    try {
        // Insere o usuário na tabela Usuarios
        $sql = "INSERT INTO Usuarios (email, senha, tipo_usuario, data_cadastro) VALUES (:email, :senha, :tipo_usuario, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':senha', $senha);
        $stmt->bindParam(':tipo_usuario', $tipo_usuario);
        $stmt->execute();

        // Obtém o ID do usuário recém-criado
        $id_usuario = $conn->lastInsertId();

        // Insere na tabela Alunos ou Tutores, dependendo do tipo de usuário
        if ($tipo_usuario === 'aluno') {
            $sql_aluno = "INSERT INTO Alunos (id_usuario, nome, data_cadastro) VALUES (:id_usuario, :nome, NOW())";
            $stmt_aluno = $conn->prepare($sql_aluno);
            $stmt_aluno->bindParam(':id_usuario', $id_usuario);
            $stmt_aluno->bindParam(':nome', $nome);
            $stmt_aluno->execute();
        } else {
            $sql_tutor = "INSERT INTO Tutores (id_usuario, nome, data_cadastro) VALUES (:id_usuario, :nome, NOW())";
            $stmt_tutor = $conn->prepare($sql_tutor);
            $stmt_tutor->bindParam(':id_usuario', $id_usuario);
            $stmt_tutor->bindParam(':nome', $nome);
            $stmt_tutor->execute();
        }

        // Redireciona para a página de login ou dashboard
        header("Location: login.php?success=Cadastro realizado com sucesso!");
        exit();

    } catch (PDOException $e) {
        // Se houver um erro, armazena na sessão e redireciona
        $_SESSION['error'] = "Erro ao cadastrar: " . $e->getMessage();
        header("Location: cadastro.php");
        exit();
    }
} else {
    $_SESSION['error'] = "Método de requisição inválido.";
    header("Location: cadastro.php");
    exit();
}

// Fechar a conexão
$conn = null;
?>
