<?php
require_once '../conexao.php';

// Início da sessão e verificação de autenticação
session_start();
if (!isset($_SESSION['id_aluno']) && !isset($_SESSION['id_tutor'])) {
    header("Location: ../login.php");
    exit();
}

$id_usuario = isset($_SESSION['id_aluno']) ? $_SESSION['id_aluno'] : $_SESSION['id_tutor'];
$tipo_usuario = isset($_SESSION['id_aluno']) ? 'aluno' : 'tutor';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $cidade = $_POST['cidade'];
    $estado = $_POST['estado'];
    $data_nascimento = $_POST['data_nascimento'];
    $biografia = $_POST['biografia'];

    if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {
        $foto_perfil = 'uploads/' . basename($_FILES['foto_perfil']['name']);
        move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $foto_perfil);
    } else {
        $foto_perfil = null;
    }

    try {
        $sql = "UPDATE " . ($tipo_usuario === 'aluno' ? 'Alunos' : 'Tutores') . " SET nome = :nome, email = :email, cidade = :cidade, estado = :estado, data_nascimento = :data_nascimento, biografia = :biografia";
        
        if ($foto_perfil) {
            $sql .= ", foto_perfil = :foto_perfil";
        }
        
        $sql .= " WHERE id = :id";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':cidade', $cidade);
        $stmt->bindParam(':estado', $estado);
        $stmt->bindParam(':data_nascimento', $data_nascimento);
        $stmt->bindParam(':biografia', $biografia);
        $stmt->bindParam(':id', $id_usuario);

        if ($foto_perfil) {
            $stmt->bindParam(':foto_perfil', $foto_perfil);
        }

        $stmt->execute();

        $idiomas = $_POST['idiomas'] ?? [];

        $stmt = $conn->prepare("DELETE FROM " . ($tipo_usuario === 'aluno' ? 'IdiomaAluno' : 'IdiomaTutor') . " WHERE " . ($tipo_usuario === 'aluno' ? 'aluno_id' : 'id_tutor') . " = :id");
        $stmt->bindParam(':id', $id_usuario);
        $stmt->execute();

        $stmt = $conn->prepare("INSERT INTO " . ($tipo_usuario === 'aluno' ? 'IdiomaAluno' : 'IdiomaTutor') . " (idioma, " . ($tipo_usuario === 'aluno' ? 'aluno_id' : 'id_tutor') . ") VALUES (:idioma, :id)");
        foreach ($idiomas as $idioma) {
            $idioma = trim($idioma);
            if ($idioma) {
                $stmt->bindParam(':idioma', $idioma);
                $stmt->bindParam(':id', $id_usuario);
                $stmt->execute();
            }
        }

        header("Location: perfil.php");
        exit();
    } catch (PDOException $e) {
        error_log("Erro ao atualizar perfil: " . $e->getMessage());
        header("Location: perfil.php?erro=1");
        exit();
    }
}
?>
