<?php 
session_start();

// Ativa a exibição de erros para debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Verifica se o ID do tutor foi passado na URL e é válido
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "ID do tutor inválido ou não fornecido.";
    exit();
}

// Obtém o ID do tutor da URL
$id_tutor = intval($_GET['id']);

require_once '../conexao.php'; // Inclui a conexão com o banco

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consulta para obter os dados do tutor
    $stmt = $pdo->prepare("SELECT * FROM Tutores WHERE id = :id");
    $stmt->execute(['id' => $id_tutor]);
    $tutor = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verifica se o tutor foi encontrado
    if (!$tutor) {
        echo "Tutor não encontrado.";
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
    <title>Perfil de Tutor - <?php echo htmlspecialchars($tutor['nome']); ?></title>
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

    <!-- Detalhes do Tutor -->
    <div class="main-content">
        <h2>Perfil de Tutor: <?php echo htmlspecialchars($tutor['nome']); ?></h2>
        <p><strong>Cidade:</strong> <?php echo htmlspecialchars($tutor['cidade']); ?></p>
        <p><strong>Estado:</strong> <?php echo htmlspecialchars($tutor['estado']); ?></p>
        <p><strong>Idiomas:</strong> <?php echo htmlspecialchars($tutor['idiomas']); ?></p>

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
