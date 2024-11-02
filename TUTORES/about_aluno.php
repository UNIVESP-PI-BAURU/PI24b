<?php 
session_start();

// Ativa a exibição de erros para debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Verifica se o ID do aluno foi passado na URL e é válido
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "ID do aluno inválido ou não fornecido.";
    exit();
}

// Obtém o ID do aluno da URL
$id_aluno = intval($_GET['id']);

require_once '../conexao.php'; // Inclui a conexão com o banco

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consulta para obter os dados do aluno
    $stmt = $pdo->prepare("SELECT * FROM Alunos WHERE id = :id");
    $stmt->execute(['id' => $id_aluno]);
    $aluno = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verifica se o aluno foi encontrado
    if (!$aluno) {
        echo "Aluno não encontrado.";
        exit();
    }

} catch (PDOException $e) {
    echo "Erro na conexão ou consulta: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Aluno - <?php echo htmlspecialchars($aluno['nome']); ?></title>
    <link rel="stylesheet" href="ASSETS/CSS/style.css">
</head>
<body>

    <!-- Cabeçalho -->
    <div class="header">
        <img src="ASSETS/IMG/capa.png" alt="Imagem de Capa">
    </div>

    <!-- Navegação -->
    <nav class="navbar">
        <a href="./index.php">Home</a>
        <a href="./sobre_nos.php">Sobre nós</a>
        <a href="./dashboard.php">Dashboard</a>
    </nav>

    <!-- Detalhes do Aluno -->
    <div class="main-content">
        <h2>Perfil de Aluno: <?php echo htmlspecialchars($aluno['nome']); ?></h2>
        <p><strong>Cidade:</strong> <?php echo htmlspecialchars($aluno['cidade']); ?></p>
        <p><strong>Estado:</strong> <?php echo htmlspecialchars($aluno['estado']); ?></p>
        <p><strong>Idiomas:</strong> <?php echo htmlspecialchars($aluno['idiomas']); ?></p>

        <!-- Funcionalidades de interação (a serem implementadas posteriormente) -->
        <div class="interactions">
            <h3>Interação</h3>
            <button>Enviar Mensagem</button> <!-- Exemplo de botão de interação -->
            <button>Adicionar aos Favoritos</button> <!-- Exemplo de botão de interação -->
        </div>
    </div>

    <!-- Rodapé -->
    <div class="footer">
        UNIVESP PI 2024
    </div>

</body>
</html>
