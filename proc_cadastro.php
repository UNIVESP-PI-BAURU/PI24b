<?php
// Ativar exibição de erros
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

// Conectar ao banco de dados
include 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Coletando os dados do formulário
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_BCRYPT); // Criptografando a senha
    $tipo_usuario = $_POST['tipo_usuario'];
    $idioma = $_POST['idioma'];

    // Inserir os dados na tabela Usuarios
    $stmt = $conn->prepare("INSERT INTO Usuarios (nome, email, senha, tipo_usuario) VALUES (:nome, :email, :senha, :tipo_usuario)");
    $stmt->bindValue(':nome', $nome);
    $stmt->bindValue(':email', $email);
    $stmt->bindValue(':senha', $senha);
    $stmt->bindValue(':tipo_usuario', $tipo_usuario);
    
    if ($stmt->execute()) {
        // Pegando o id do usuário inserido
        $id_usuario = $conn->lastInsertId();

        // Inserir o idioma na tabela correspondente
        if ($tipo_usuario === 'aluno') {
            $stmt_idioma = $conn->prepare("INSERT INTO IdiomaAluno (idioma, id_aluno) VALUES (:idioma, :id_aluno)");
            $stmt_idioma->bindValue(':idioma', $idioma);
            $stmt_idioma->bindValue(':id_aluno', $id_usuario);
        } else {
            $stmt_idioma = $conn->prepare("INSERT INTO IdiomaTutor (idioma, id_tutor) VALUES (:idioma, :id_tutor)");
            $stmt_idioma->bindValue(':idioma', $idioma);
            $stmt_idioma->bindValue(':id_tutor', $id_usuario);
        }

        // Executa a inserção do idioma
        if ($stmt_idioma->execute()) {
            $_SESSION['success'] = "Cadastro realizado com sucesso!";
            header("Location: login.php");
            exit();
        } else {
            $_SESSION['error'] = "Erro ao cadastrar idioma.";
            header("Location: cadastro.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "Erro ao cadastrar usuário.";
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
