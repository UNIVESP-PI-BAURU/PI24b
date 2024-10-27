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

    // Atualiza os dados pessoais
    $sql = "UPDATE $tabela_usuario SET nome = ?, email = ?, cidade = ?, estado = ?, data_nascimento = ?, biografia = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$nome, $email, $cidade, $estado, $data_nascimento, $biografia, $id_usuario]);

    // Atualiza os idiomas (se houver)
    if (isset($_POST['idiomas'])) {
        $idiomas = $_POST['idiomas']; // Espera um array de idiomas

        // Remove os idiomas antigos
        $sql_delete = "DELETE FROM IdiomaAluno WHERE id_aluno = :id_aluno";
        $stmt_delete = $conn->prepare($sql_delete);
        $stmt_delete->bindParam(':id_aluno', $id_usuario, PDO::PARAM_INT);
        $stmt_delete->execute();

        // Insere os novos idiomas
        foreach ($idiomas as $idioma) {
            $sql_insert = "INSERT INTO IdiomaAluno (id_aluno, idioma) VALUES (:id_aluno, :idioma)";
            $stmt_insert = $conn->prepare($sql_insert);
            $stmt_insert->bindParam(':id_aluno', $id_usuario, PDO::PARAM_INT);
            $stmt_insert->bindParam(':idioma', $idioma, PDO::PARAM_STR);
            $stmt_insert->execute();
        }
    }

    // Redireciona de volta para o perfil
    header("Location: ./perfil.php");
    exit();
}
?>
