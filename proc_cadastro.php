<?php
session_start();
require_once 'conexao.php'; // Inclui a conexão com o banco

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recebe os dados do formulário
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT); // Hash da senha
    $tipo_usuario = $_POST['tipo_usuario']; // Tipo de usuário selecionado
    $nome = $_POST['nome']; // Nome do usuário
    $idiomas = $_POST['idiomas']; // Idiomas selecionados (array)

    try {
        // Inicia a transação para garantir integridade dos dados
        $conn->beginTransaction();

        // 1. Insere o usuário na tabela Usuarios
        $sql_usuario = "INSERT INTO Usuarios (email, senha, tipo_usuario, data_cadastro) VALUES (:email, :senha, :tipo_usuario, NOW())";
        $stmt_usuario = $conn->prepare($sql_usuario);
        $stmt_usuario->bindParam(':email', $email);
        $stmt_usuario->bindParam(':senha', $senha);
        $stmt_usuario->bindParam(':tipo_usuario', $tipo_usuario);
        $stmt_usuario->execute();

        // Obtém o ID do usuário recém-criado
        $id_usuario = $conn->lastInsertId();

        // 2. Insere os dados do tipo de usuário (Aluno ou Tutor)
        if ($tipo_usuario === 'aluno') {
            // Insere o aluno na tabela Alunos
            $sql_aluno = "INSERT INTO Alunos (id_usuario, nome, data_cadastro) VALUES (:id_usuario, :nome, NOW())";
            $stmt_aluno = $conn->prepare($sql_aluno);
            $stmt_aluno->bindParam(':id_usuario', $id_usuario);
            $stmt_aluno->bindParam(':nome', $nome);
            $stmt_aluno->execute();

            // 3. Insere os idiomas na tabela IdiomaAluno
            foreach ($idiomas as $idioma) {
                $sql_idioma_aluno = "INSERT INTO IdiomaAluno (id_aluno, idioma) VALUES (:id_aluno, :idioma)";
                $stmt_idioma_aluno = $conn->prepare($sql_idioma_aluno);
                $stmt_idioma_aluno->bindParam(':id_aluno', $id_usuario); // Usa o mesmo ID de usuário como ID de aluno
                $stmt_idioma_aluno->bindParam(':idioma', $idioma);
                $stmt_idioma_aluno->execute();
            }
        } elseif ($tipo_usuario === 'tutor') {
            // Insere o tutor na tabela Tutores
            $sql_tutor = "INSERT INTO Tutores (id_usuario, nome, data_cadastro) VALUES (:id_usuario, :nome, NOW())";
            $stmt_tutor = $conn->prepare($sql_tutor);
            $stmt_tutor->bindParam(':id_usuario', $id_usuario);
            $stmt_tutor->bindParam(':nome', $nome);
            $stmt_tutor->execute();

            // 3. Insere os idiomas na tabela IdiomaTutor
            foreach ($idiomas as $idioma) {
                $sql_idioma_tutor = "INSERT INTO IdiomaTutor (id_tutor, idioma) VALUES (:id_tutor, :idioma)";
                $stmt_idioma_tutor = $conn->prepare($sql_idioma_tutor);
                $stmt_idioma_tutor->bindParam(':id_tutor', $id_usuario); // Usa o mesmo ID de usuário como ID de tutor
                $stmt_idioma_tutor->bindParam(':idioma', $idioma);
                $stmt_idioma_tutor->execute();
            }
        }

        // 4. Commit da transação
        $conn->commit();

        // Redireciona para a página de login ou dashboard
        header("Location: login.php?success=Cadastro realizado com sucesso!");
        exit();

    } catch (PDOException $e) {
        // Se houver um erro, reverte a transação e armazena o erro
        $conn->rollBack();
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
