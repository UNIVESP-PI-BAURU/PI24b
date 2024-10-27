<?php
// Inicia a sessão
session_start();
if (!isset($_SESSION['id_aluno']) && !isset($_SESSION['id_tutor'])) {
    die("Usuário não está logado."); // Mensagem de erro se não estiver logado
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
$sql = "SELECT nome, email, foto_perfil, cidade, estado, data_nascimento, biografia, idiomas 
        FROM $tabela_usuario 
        WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $id_usuario, PDO::PARAM_INT);
$stmt->execute();

// Verifica se a consulta foi bem-sucedida
if ($stmt->errorCode() != '00000') {
    die("Erro na consulta SQL: " . implode(", ", $stmt->errorInfo())); // Mensagem de erro se houver problema na consulta
}

$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

// Verifica se encontrou o usuário
if (!$usuario) {
    die("Usuário não encontrado."); // Mensagem de erro se o usuário não for encontrado
}

// Processa idiomas (se houver)
$idiomas = isset($usuario['idiomas']) ? explode(',', $usuario['idiomas']) : [];
?>
