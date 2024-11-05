<?php
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
    $stmt = $conn->prepare("INSERT INTO Usuarios (nome, email, senha, tipo_usuario) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nome, $email, $senha, $tipo_usuario);
    
    if ($stmt->execute()) {
        // Pegando o id do usuário inserido
        $id_usuario = $stmt->insert_id;

        // Inserir o idioma na tabela correspondente (IdiomaAluno ou IdiomaTutor)
        if ($tipo_usuario === 'aluno') {
            $stmt_idioma = $conn->prepare("INSERT INTO IdiomaAluno (idioma, id_aluno) VALUES (?, ?)");
            $stmt_idioma->bind_param("si", $idioma, $id_usuario);
        } else {
            $stmt_idioma = $conn->prepare("INSERT INTO IdiomaTutor (idioma, id_tutor) VALUES (?, ?)");
            $stmt_idioma->bind_param("si", $idioma, $id_usuario);
        }

        // Executa a inserção do idioma
        if ($stmt_idioma->execute()) {
            // Cadastro bem-sucedido
            $_SESSION['success'] = "Cadastro realizado com sucesso!";
            header("Location: login.php"); // Redireciona para login
            exit();
        } else {
            // Erro ao cadastrar idioma
            $_SESSION['error'] = "Erro ao cadastrar idioma.";
            header("Location: cadastro.php");
            exit();
        }
    } else {
        // Erro ao cadastrar usuário
        $_SESSION['error'] = "Erro ao cadastrar usuário.";
        header("Location: cadastro.php");
        exit();
    }

} else {
    // Se o método de requisição não for POST
    $_SESSION['error'] = "Método de requisição inválido.";
    header("Location: cadastro.php");
    exit();
}

// Fechar a conexão
$conn->close();
?>
