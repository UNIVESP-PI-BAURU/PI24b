<?php
session_start(); // Inicia a sessão

require_once '../conexao.php'; // Inclui a conexão com o banco

// Verifica se o usuário está logado
if (isset($_SESSION['id_aluno'])) {
    $id_usuario = $_SESSION['id_aluno'];
    $tipo_usuario = 'aluno';
    $tipo_conversor = 'tutor'; // Define o tipo_conversor conforme necessário
} elseif (isset($_SESSION['id_tutor'])) {
    $id_usuario = $_SESSION['id_tutor'];
    $tipo_usuario = 'tutor';
    $tipo_conversor = 'aluno'; // Define o tipo_conversor conforme necessário
} else {
    header("Location: ../login.php"); // Redireciona se não estiver logado
    exit();
}

// Recupera os dados do usuário
try {
    if ($tipo_usuario === 'aluno') {
        $query = $conn->prepare("SELECT * FROM Alunos WHERE id = :id");
    } else {
        $query = $conn->prepare("SELECT * FROM Tutores WHERE id = :id");
    }

    $query->bindParam(':id', $id_usuario);
    $query->execute();
    $usuario = $query->fetch(PDO::FETCH_ASSOC);

    // Se o usuário não for encontrado, redireciona
    if (!$usuario) {
        header("Location: ../login.php");
        exit();
    }

    // Recupera idiomas se necessário
    $idiomas = [];
    if ($tipo_usuario === 'aluno') {
        $query_idiomas = $conn->prepare("SELECT idioma FROM IdiomaAluno WHERE id_aluno = :id");
    } else {
        $query_idiomas = $conn->prepare("SELECT idioma FROM IdiomaTutor WHERE id_tutor = :id");
    }

    $query_idiomas->bindParam(':id', $id_usuario);
    $query_idiomas->execute();
    $idiomas = $query_idiomas->fetchAll(PDO::FETCH_COLUMN);

} catch (PDOException $e) {
    header("Location: ../login.php"); // Redireciona em caso de erro
    exit();
}
?>
