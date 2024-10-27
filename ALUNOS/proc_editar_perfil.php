<?php
// Inicia a sessão
session_start();
if (!isset($_SESSION['id_aluno']) && !isset($_SESSION['id_tutor'])) {
    header("Location: ../login.php");
    exit();
}

// Conexão com o banco de dados
require_once '../conexao.php';
if (!$conn) {
    die("Falha na conexão com o banco de dados.");
}

// Identifica o tipo de usuário e obtém o ID
$tipo_usuario = isset($_SESSION['id_aluno']) ? 'aluno' : 'tutor';
$id_usuario = $_SESSION['id_' . $tipo_usuario];

// Recupera os dados do usuário
$sql = "SELECT nome, email, cidade, estado, data_nascimento, biografia 
        FROM Alunos WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $id_usuario);
$stmt->execute();
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

// Recupera os idiomas do usuário
$sql_idiomas = "SELECT idioma FROM IdiomaAluno WHERE id_aluno = :id_aluno";
$stmt_idiomas = $conn->prepare($sql_idiomas);
$stmt_idiomas->bindParam(':id_aluno', $id_usuario);
$stmt_idiomas->execute();
$idiomas_aluno = $stmt_idiomas->fetchAll(PDO::FETCH_COLUMN);

// Processa a atualização se for uma requisição POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $cidade = $_POST['cidade'];
    $estado = $_POST['estado'];
    $data_nascimento = $_POST['data_nascimento'];
    $biografia = $_POST['biografia'];
    
    // Atualiza os dados pessoais
    $sql_update = "UPDATE Alunos SET nome = ?, email = ?, cidade = ?, estado = ?, data_nascimento = ?, biografia = ? WHERE id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->execute([$nome, $email, $cidade, $estado, $data_nascimento, $biografia, $id_usuario]);

    // Recupera os idiomas do usuário
    $idiomas = array_map('trim', $_POST['idiomas']); // Obtém todos os idiomas

    // Remove os idiomas antigos
    $sql_delete = "DELETE FROM IdiomaAluno WHERE id_aluno = ?";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->execute([$id_usuario]);

    // Insere os novos idiomas
    foreach ($idiomas as $idioma) {
        if (!empty($idioma)) { // Verifica se o idioma não está vazio
            $sql_insert = "INSERT INTO IdiomaAluno (id_aluno, idioma) VALUES (?, ?)";
            $stmt_insert = $conn->prepare($sql_insert);
            $stmt_insert->execute([$id_usuario, $idioma]);
        }
    }

    // Redireciona para o perfil
    header("Location: ./perfil.php");
    exit();
}
?>
