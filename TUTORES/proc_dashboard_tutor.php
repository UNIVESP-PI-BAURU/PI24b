<?php
session_start(); // Inicia a sessão

// Verifica se o tutor está logado e redireciona para login se não estiver
if (!isset($_SESSION['id_tutor'])) {
    header("Location: ../login.php");
    exit();
}

require_once '../conexao.php'; // Inclui a conexão com o banco

// Define o tipo de usuário como tutor
$tipo_usuario = 'tutor';
$id_usuario = $_SESSION['id_tutor'];
$tabela_usuario = 'Tutores';

// Consulta os dados do tutor
$sql = "SELECT nome, foto_perfil, cidade, estado FROM $tabela_usuario WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $id_usuario);
$stmt->execute();
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

// Finaliza a execução se o tutor não for encontrado
if (!$usuario) {
    header("Location: ../login.php");
    exit();
}

// O array $usuario agora está disponível para uso na dashboard de tutor
?>
