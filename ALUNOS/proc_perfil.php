<?php
// Inicia a sessão
session_start();
if (!isset($_SESSION['id_aluno']) && !isset($_SESSION['id_tutor'])) {
    header("Location: ../login.php");
    exit();
}

// Incluir conexão com o banco de dados
require_once '../conexao.php';
if (!$conn) {
    die("Falha na conexão com o banco de dados.");
}

// Determina o tipo de usuário e busca os dados
$tipo_usuario = isset($_SESSION['id_aluno']) ? 'aluno' : 'tutor';
$id_usuario = $_SESSION['id_' . $tipo_usuario];
$tabela_usuario = ($tipo_usuario === 'aluno') ? 'Alunos' : 'Tutores';

// Consulta SQL para buscar dados do usuário
$sql = "SELECT nome, email, foto_perfil, cidade, estado, data_nascimento, biografia 
        FROM $tabela_usuario 
        WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $id_usuario, PDO::PARAM_INT);
$stmt->execute();

$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

// Verifica se encontrou o usuário
if (!$usuario) {
    die("Usuário não encontrado.");
}

// Consulta SQL para buscar idiomas do usuário
$sql_idiomas = "SELECT idioma FROM IdiomaAluno WHERE id_aluno = :id_aluno";
$stmt_idiomas = $conn->prepare($sql_idiomas);
$stmt_idiomas->bindParam(':id_aluno', $id_usuario, PDO::PARAM_INT);
$stmt_idiomas->execute();

$idiomas = $stmt_idiomas->fetchAll(PDO::FETCH_COLUMN);

// Verifica se encontrou idiomas
if (!$idiomas) {
    $idiomas = []; // Caso não tenha idiomas, inicializa como array vazio
}
?>
