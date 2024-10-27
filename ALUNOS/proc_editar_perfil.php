<?php
// Inicia a sessão
session_start();
if (!isset($_SESSION['id_aluno']) && !isset($_SESSION['id_tutor'])) {
    die("Usuário não está logado.");
}

// Incluir conexão com o banco de dados
require_once '../conexao.php';
if (!$conn) {
    die("Falha na conexão com o banco de dados.");
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
    $idiomas = $_POST['idioma'];  // Array de idiomas do formulário

    // Atualiza os dados pessoais na tabela 'Alunos'
    $sql = "UPDATE Alunos SET nome = ?, email = ?, cidade = ?, estado = ?, data_nascimento = ?, biografia = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt->execute([$nome, $email, $cidade, $estado, $data_nascimento, $biografia, $id_usuario])) {
        die("Erro ao atualizar dados do usuário: " . implode(", ", $stmt->errorInfo()));
    }

    // Atualiza os idiomas na tabela 'IdiomaAluno'
    // Remove todos os idiomas antigos
    $sql_delete = "DELETE FROM IdiomaAluno WHERE id_aluno = ?";
    $stmt_delete = $conn->prepare($sql_delete);
    if (!$stmt_delete->execute([$id_usuario])) {
        die("Erro ao deletar idiomas antigos: " . implode(", ", $stmt_delete->errorInfo()));
    }

    // Insere os novos idiomas
    if (!empty($idiomas)) {
        foreach ($idiomas as $idioma) {
            $sql_insert = "INSERT INTO IdiomaAluno (id_aluno, idioma) VALUES (?, ?)";
            $stmt_insert = $conn->prepare($sql_insert);
            if (!$stmt_insert->execute([$id_usuario, $idioma])) {
                die("Erro ao inserir novos idiomas: " . implode(", ", $stmt_insert->errorInfo()));
            }
        }
    }

    // Redireciona para o perfil
    header("Location: ./perfil.php");
    exit();
}
?>
