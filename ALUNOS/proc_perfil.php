<?php
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
$sql = "SELECT nome, email, foto_perfil, cidade, estado, data_nascimento, biografia, idiomas 
        FROM $tabela_usuario 
        WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $id_usuario, PDO::PARAM_INT);
$stmt->execute();
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

// Verifica se encontrou o usuário
if (!$usuario) {
    echo "<p>Usuário não encontrado.</p>";
    exit();
}

// Processa idiomas (se houver)
$idiomas = isset($usuario['idiomas']) ? explode(',', $usuario['idiomas']) : [];
?>
