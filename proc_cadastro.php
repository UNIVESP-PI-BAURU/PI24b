<?php
session_start();
require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
    $tipo_usuario = $_POST['tipo_usuario'];
    $idioma = $_POST['idioma']; // Corrigido de 'idiomas' para 'idioma'

    try {
        $conn->beginTransaction();

        if ($tipo_usuario === 'aluno') {
            $table = 'Alunos';
            $tipo_valor = 'aluno';
        } elseif ($tipo_usuario === 'tutor') {
            $table = 'Tutores';
            $tipo_valor = 'tutor';
        } else {
            throw new Exception("Tipo de usuário inválido.");
        }

        $check_email = "SELECT * FROM $table WHERE email = :email";
        $stmt_check = $conn->prepare($check_email);
        $stmt_check->bindParam(':email', $email);
        $stmt_check->execute();

        if ($stmt_check->rowCount() > 0) {
            $_SESSION['error'] = "Este e-mail já está registrado.";
            header("Location: cadastro.php");
            exit();
        }

        // Corrigindo o nome da coluna para 'idioma' (não mais 'idiomas')
        $sql = "INSERT INTO $table (nome, email, senha, tipo, data_cadastro, idioma) 
                VALUES (:nome, :email, :senha, :tipo, NOW(), :idioma)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':senha', $senha);
        $stmt->bindParam(':tipo', $tipo_valor);
        $stmt->bindParam(':idioma', $idioma); // Corrigido aqui também
        $stmt->execute();

        $conn->commit();

        header("Location: login.php?success=Cadastro realizado com sucesso!");
        exit();

    } catch (PDOException $e) {
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

$conn = null;
?>
