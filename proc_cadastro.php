<?php
session_start();
require_once 'conexao.php'; // Inclui a conexão com o banco

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recebe os dados do formulário
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT); // Hash da senha
    $tipo_usuario = $_POST['tipo_usuario']; // Tipo de usuário selecionado: aluno ou tutor
    $idiomas = $_POST['idiomas_list']; // Idiomas listados

    try {
        // Inicia a transação para garantir integridade dos dados
        $conn->beginTransaction();

        // Determina a tabela com base no tipo de usuário
        if ($tipo_usuario === 'aluno') {
            $table = 'Alunos';
            $tipo_valor = 'aluno';
        } elseif ($tipo_usuario === 'tutor') {
            $table = 'Tutores';
            $tipo_valor = 'tutor';
        } else {
            throw new Exception("Tipo de usuário inválido.");
        }

        // Verifica se o e-mail já está cadastrado
        $check_email = "SELECT * FROM $table WHERE email = :email";
        $stmt_check = $conn->prepare($check_email);
        $stmt_check->bindParam(':email', $email);
        $stmt_check->execute();
        
        if ($stmt_check->rowCount() > 0) {
            $_SESSION['error'] = "Este e-mail já está registrado.";
            header("Location: cadastro.php");
            exit();
        }

        // Insere o usuário na tabela correspondente
        $sql = "INSERT INTO $table (nome, email, senha, tipo, data_cadastro, idiomas) 
                VALUES (:nome, :email, :senha, :tipo, NOW(), :idiomas)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':senha', $senha);
        $stmt->bindParam(':tipo', $tipo_valor);
        $stmt->bindParam(':idiomas', $idiomas); // Insere os idiomas
        $stmt->execute();

        // Confirma a transação
        $conn->commit();

        // Redireciona para a página de login com sucesso
        header("Location: login.php?success=Cadastro realizado com sucesso!");
        exit();

    } catch (PDOException $e) {
        // Em caso de erro, reverte a transação e exibe mensagem
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

// Fecha a conexão
$conn = null;
?>
