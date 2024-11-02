<?php
session_start();
require_once '../conexao.php';

// Verifica se o usuário está logado
if (isset($_SESSION['id_aluno'])) {
    $id_usuario = $_SESSION['id_aluno'];
    $tipo_usuario = 'aluno';
} elseif (isset($_SESSION['id_tutor'])) {
    $id_usuario = $_SESSION['id_tutor'];
    $tipo_usuario = 'tutor';
} else {
    header("Location: ../login.php");
    exit();
}

// Recupera os dados do usuário
if ($tipo_usuario === 'aluno') {
    $query = $pdo->prepare("SELECT * FROM Alunos WHERE id = :id");
} else {
    $query = $pdo->prepare("SELECT * FROM Tutores WHERE id = :id");
}

$query->bindParam(':id', $id_usuario);
$query->execute();

$usuario = $query->fetch(PDO::FETCH_ASSOC);

// Se o usuário não for encontrado, redireciona
if (!$usuario) {
    header("Location: ../login.php");
    exit();
}

// Recupera idiomas se necessário
$idiomas = [];
if ($tipo_usuario === 'aluno') {
    $query_idiomas = $pdo->prepare("SELECT idioma FROM IdiomaAluno WHERE aluno_id = :id");
} else {
    $query_idiomas = $pdo->prepare("SELECT idioma FROM IdiomaTutor WHERE tutor_id = :id");
}

$query_idiomas->bindParam(':id', $id_usuario);
$query_idiomas->execute();
$idiomas = $query_idiomas->fetchAll(PDO::FETCH_COLUMN);

// Renderiza o perfil
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Perfil</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <h1><?php echo htmlspecialchars($usuario['nome']); ?></h1>
        <p>ID: <?php echo htmlspecialchars($usuario['id']); ?></p> <!-- Exibe o ID do usuário -->
        
        <h2>Idiomas</h2>
        <ul>
            <?php foreach ($idiomas as $idioma): ?>
                <li><?php echo htmlspecialchars($idioma); ?></li>
            <?php endforeach; ?>
        </ul>
        
        <a href="../index.php">Voltar</a>
    </div>
</body>
</html>
