<?php
// Inicia a sessão e verifica login
session_start();
if (!isset($_SESSION['id']) || !isset($_SESSION['tipo'])) {
    header("Location: login.php");
    exit();
}

// Inclui a conexão com o banco
require_once 'conexao.php';

// Determina o tipo de usuário e busca os dados
$tipo_usuario = $_SESSION['tipo']; // 'aluno' ou 'tutor'
$id_usuario = $_SESSION['id']; // ID comum para todos os tipos
$tabela_usuario = ($tipo_usuario === 'aluno') ? 'Alunos' : 'Tutores';

try {
    // Consulta SQL para buscar dados do usuário
    $sql = "SELECT nome, email, foto_perfil, cidade, estado, data_nascimento, biografia, idioma
            FROM $tabela_usuario WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id_usuario, PDO::PARAM_INT);
    $stmt->execute();

    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verifica se encontrou o usuário
    if (!$usuario) {
        die("Usuário não encontrado.");
    }

    // Extrai o idioma
    $idioma = !empty($usuario['idioma']) ? $usuario['idioma'] : 'Não informado';
} catch (PDOException $e) {
    echo "Erro ao carregar perfil: " . $e->getMessage();
    exit();
}
?>
