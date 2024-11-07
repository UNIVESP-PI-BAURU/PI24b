<?php
session_start();
require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
    $tipo_usuario = $_POST['tipo_usuario'];
    $idiomas = explode(',', $_POST['idiomas_list']);

    try {
        $conn->beginTransaction();

        if ($tipo_usuario === 'aluno') {
            $table = 'Alunos';
            $idiomaTable = 'IdiomaAluno';
            $tipo_valor = 'aluno';
        } elseif ($tipo_usuario === 'tutor') {
            $table = 'Tutores';
            $idiomaTable = 'IdiomaTutor';
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

        $sql = "INSERT INTO $table (nome, email, senha, tipo, data_cadastro) 
                VALUES (:nome, :email, :senha, :tipo, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':senha', $senha);
        $stmt->bindParam(':tipo', $tipo_valor);
        $stmt->execute();

        $user_id = $conn->lastInsertId();

        $sqlIdioma = "INSERT INTO $idiomaTable (id_user, idioma) VALUES (:id_user, :idioma)";
        $stmtIdioma = $conn->prepare($sqlIdioma);

        foreach ($idiomas as $idioma) {
            $stmtIdioma->bindParam(':id_user', $user_id);
            $stmtIdioma->bindParam(':idioma', trim($idioma));
            $stmtIdioma->execute();
        }

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
