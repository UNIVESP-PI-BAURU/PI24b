<?php
session_start();
require_once '../conexao.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['id_aluno']) && !isset($_SESSION['id_tutor'])) {
    die("Usuário não está logado."); // Mensagem de erro se não estiver logado
}

// Verifica se a requisição é POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recupera os dados do formulário
    $id_usuario = $_POST['id_usuario'];
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $cidade = $_POST['cidade'];
    $estado = $_POST['estado'];
    $data_nascimento = $_POST['data_nascimento'];
    $biografia = $_POST['biografia'];

    // Atualiza os dados no banco
    $tipo_usuario = isset($_SESSION['id_aluno']) ? 'aluno' : 'tutor';
    $tabela_usuario = ($tipo_usuario === 'aluno') ? 'Alunos' : 'Tutores';

    // Atualiza os dados pessoais, excluindo o campo 'idiomas'
    $sql = "UPDATE $tabela_usuario SET nome = ?, email = ?, cidade = ?, estado = ?, data_nascimento = ?, biografia = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$nome, $email, $cidade, $estado, $data_nascimento, $biografia, $id_usuario]);

    // Redireciona de volta para o perfil
    header("Location: ./perfil.php");
    exit();
}
?>
