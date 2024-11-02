<?php
session_start();
require_once '../conexao.php';

// Verifica se o usuário está logado
if (isset($_SESSION['id_aluno'])) {
    $id_usuario = $_SESSION['id_aluno'];
    $tipo_usuario = 'aluno';
    error_log("Usuário logado como aluno: ID = $id_usuario"); // Debug: ID do aluno
} elseif (isset($_SESSION['id_tutor'])) {
    $id_usuario = $_SESSION['id_tutor'];
    $tipo_usuario = 'tutor';
    error_log("Usuário logado como tutor: ID = $id_usuario"); // Debug: ID do tutor
} else {
    error_log("Usuário não logado, redirecionando para login."); // Debug: não logado
    header("Location: ../login.php");
    exit();
}

// Recupera os dados do usuário
if ($tipo_usuario === 'aluno') {
    $query = $conn->prepare("SELECT * FROM Alunos WHERE id = :id");
    error_log("Query para recuperar aluno: " . $query->queryString); // Debug: Query do aluno
} else {
    $query = $conn->prepare("SELECT * FROM Tutores WHERE id = :id");
    error_log("Query para recuperar tutor: " . $query->queryString); // Debug: Query do tutor
}

$query->bindParam(':id', $id_usuario);
$query->execute();

$usuario = $query->fetch(PDO::FETCH_ASSOC);

// Se o usuário não for encontrado, redireciona
if (!$usuario) {
    error_log("Usuário não encontrado: ID = $id_usuario, redirecionando para login."); // Debug: usuário não encontrado
    header("Location: ../login.php");
    exit();
}

// Recupera idiomas se necessário
$idiomas = [];
if ($tipo_usuario === 'aluno') {
    $query_idiomas = $conn->prepare("SELECT idioma FROM IdiomaAluno WHERE aluno_id = :id");
    error_log("Query para recuperar idiomas do aluno: " . $query_idiomas->queryString); // Debug: Query de idiomas do aluno
} else {
    $query_idiomas = $conn->prepare("SELECT idioma FROM IdiomaTutor WHERE tutor_id = :id");
    error_log("Query para recuperar idiomas do tutor: " . $query_idiomas->queryString); // Debug: Query de idiomas do tutor
}

$query_idiomas->bindParam(':id', $id_usuario);
$query_idiomas->execute();
$idiomas = $query_idiomas->fetchAll(PDO::FETCH_COLUMN);

// Debug: Exibe idiomas recuperados
error_log("Idiomas recuperados: " . implode(", ", $idiomas));
?>
