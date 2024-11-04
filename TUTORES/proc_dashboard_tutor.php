<?php
// Inclui a conexão com o banco de dados
require_once '../conexao.php'; 

// Verifica se o usuário está logado (tutor)
if (!isset($_SESSION['id_tutor'])) {
    header("Location: ../login.php"); // Redireciona para a página de login se não estiver logado
    exit();
}

$id_usuario = $_SESSION['id_tutor'];
$tabela_usuario = 'Tutores';

// Consulta os dados do tutor
$sql = "SELECT nome, foto_perfil, cidade, estado FROM $tabela_usuario WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $id_usuario, PDO::PARAM_INT);
$stmt->execute();
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

// Finaliza a execução se o tutor não for encontrado
if (!$usuario) {
    header("Location: ../login.php");
    exit();
}

// O array $usuario agora está disponível para uso na dashboard de tutor
?>
