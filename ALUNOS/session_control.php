<?php
session_start(); // Inicia a sessão

// Verifica se o usuário está logado (tutor ou aluno)
if (!isset($_SESSION['id_aluno']) && !isset($_SESSION['id_tutor'])) {
    header("Location: ./login.php"); // Redireciona para a página de login se não estiver logado
    exit();
}

// Inclui a conexão com o banco de dados
require_once './conexao.php'; 

// Armazena a ID do usuário e o tipo de usuário na variável
if (isset($_SESSION['id_aluno'])) {
    $id_usuario = $_SESSION['id_aluno']; // ID do aluno logado
    $tipo_usuario = 'aluno';
    $tabela_usuario = 'Alunos';
} else {
    $id_usuario = $_SESSION['id_tutor']; // ID do tutor logado
    $tipo_usuario = 'tutor';
    $tabela_usuario = 'Tutores';
}

// Consulta os dados do usuário
$sql = "SELECT nome, foto_perfil, cidade, estado, email, data_nascimento, biografia 
        FROM $tabela_usuario 
        WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $id_usuario);
$stmt->execute();
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

// Verifica se o usuário foi encontrado
if (!$usuario) {
    header("Location: ./login.php"); // Redireciona se o usuário não for encontrado
    exit();
}

// Armazena os idiomas se o usuário for aluno ou tutor
if ($tipo_usuario == 'aluno') {
    $idioma_sql = "SELECT idioma FROM IdiomaAluno WHERE id_aluno = :id";
} else {
    $idioma_sql = "SELECT idioma FROM IdiomaTutor WHERE id_tutor = :id";
}

$stmt_idioma = $conn->prepare($idioma_sql);
$stmt_idioma->bindParam(':id', $id_usuario);
$stmt_idioma->execute();
$idiomas = $stmt_idioma->fetchAll(PDO::FETCH_COLUMN);

// Você pode armazenar os idiomas na sessão se precisar
$_SESSION['idiomas'] = $idiomas;

// Agora você pode acessar as informações do usuário em outras partes do seu site
?>
