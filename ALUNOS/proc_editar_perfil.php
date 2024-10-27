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
    $idiomas = $_POST['idiomas']; // Campo que contém os idiomas

    // Debug: Mostra os valores das variáveis
    echo "<pre>";
    echo "ID do usuário: " . $id_usuario . "\n";
    echo "Nome: " . $nome . "\n";
    echo "Email: " . $email . "\n";
    echo "Cidade: " . $cidade . "\n";
    echo "Estado: " . $estado . "\n";
    echo "Data de Nascimento: " . $data_nascimento . "\n";
    echo "Biografia: " . $biografia . "\n";
    echo "Idiomas: " . implode(", ", $idiomas) . "\n"; // Debug dos idiomas
    echo "</pre>";

    // Determina o tipo de usuário
    $tipo_usuario = isset($_SESSION['id_aluno']) ? 'aluno' : 'tutor';
    $tabela_usuario = ($tipo_usuario === 'aluno') ? 'Alunos' : 'Tutores';

    // Atualiza os dados pessoais na tabela Alunos ou Tutores
    $sql = "UPDATE $tabela_usuario SET nome = ?, email = ?, cidade = ?, estado = ?, data_nascimento = ?, biografia = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt->execute([$nome, $email, $cidade, $estado, $data_nascimento, $biografia, $id_usuario])) {
        die("Erro ao atualizar dados do usuário: " . implode(", ", $stmt->errorInfo())); // Mensagem de erro se houver problema na atualização
    }

    // Atualiza os idiomas na tabela IdiomaAluno
    // Primeiramente, remove os idiomas antigos da tabela IdiomaAluno
    $sql_delete = "DELETE FROM IdiomaAluno WHERE id_aluno = ?";
    $stmt_delete = $conn->prepare($sql_delete);
    if (!$stmt_delete->execute([$id_usuario])) {
        die("Erro ao deletar idiomas antigos: " . implode(", ", $stmt_delete->errorInfo())); // Mensagem de erro se houver problema na remoção
    }

    // Agora, insere os novos idiomas na tabela IdiomaAluno
    if (!empty($idiomas)) {
        foreach ($idiomas as $idioma) {
            $sql_insert = "INSERT INTO IdiomaAluno (id_aluno, idioma) VALUES (?, ?)";
            $stmt_insert = $conn->prepare($sql_insert);
            if (!$stmt_insert->execute([$id_usuario, $idioma])) {
                die("Erro ao inserir novos idiomas: " . implode(", ", $stmt_insert->errorInfo())); // Mensagem de erro se houver problema na inserção
            }
        }
    }

    // Redireciona de volta para o perfil
    header("Location: ./perfil.php");
    exit();
}
?>
