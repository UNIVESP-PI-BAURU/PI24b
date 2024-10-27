<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['id_aluno']) && !isset($_SESSION['id_tutor'])) {
    header("Location: ../login.php");
    exit();
}

require_once '../conexao.php'; // Inclui a conexão com o banco

// Define o tipo de usuário e busca os dados
$tipo_usuario = isset($_SESSION['id_aluno']) ? 'aluno' : 'tutor';
$id_usuario = $_SESSION['id_' . $tipo_usuario];
$tabela_usuario = ($tipo_usuario === 'aluno') ? 'Alunos' : 'Tutores';

echo "<!-- Debug: tipo_usuario = $tipo_usuario, id_usuario = $id_usuario -->"; // Mensagem de depuração

// Consulta os dados do usuário
$sql = "SELECT nome, foto_perfil, cidade, estado FROM $tabela_usuario WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $id_usuario);
$stmt->execute();
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

// Verifica se os dados do usuário foram encontrados
if (!$usuario) {
    echo "<p>Usuário não encontrado.</p>";
    exit();
}

// O array $usuario agora está disponível para uso na dashboard
echo "<!-- Debug: usuario encontrado = " . json_encode($usuario) . " -->"; // Mensagem de depuração
