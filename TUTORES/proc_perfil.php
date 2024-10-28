<?php
// Inicia a sessão
session_start();
if (!isset($_SESSION['id_tutor'])) {
    header("Location: ../login.php");
    exit();
}

// Inclui conexão com o banco de dados
require_once '../conexao.php';
if (!$conn) {
    die("Falha na conexão com o banco de dados.");
}

// Define o tipo de usuário e realiza a consulta
$tipo_usuario = 'tutor';
$id_usuario = $_SESSION['id_tutor'];

// Consulta SQL para buscar dados do tutor
$sql = "SELECT nome, email, foto_perfil, cidade, estado, data_nascimento, biografia 
        FROM Tutores 
        WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $id_usuario, PDO::PARAM_INT);
$stmt->execute();

$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

// Verifica se encontrou o tutor
if (!$usuario) {
    die("Usuário não encontrado.");
}

// Consulta SQL para buscar idiomas do tutor
$sql_idiomas = "SELECT idioma FROM IdiomaTutor WHERE id_tutor = :id_tutor";
$stmt_idiomas = $conn->prepare($sql_idiomas);
$stmt_idiomas->bindParam(':id_tutor', $id_usuario, PDO::PARAM_INT);
$stmt_idiomas->execute();

$idiomas = $stmt_idiomas->fetchAll(PDO::FETCH_COLUMN);

// Verifica se encontrou idiomas
if (!$idiomas) {
    $idiomas = []; // Caso não tenha idiomas, inicializa como array vazio
}
?>
