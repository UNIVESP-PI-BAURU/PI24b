<?php 
session_start();

// Verifica se o ID do tutor foi passado na URL
if (!isset($_GET['id'])) {
    // Redireciona para a página de pesquisa se o ID não for fornecido
    header("Location: pesquisa_tutores.php");
    exit();
}

// Obtém o ID do tutor da URL
$id_tutor = intval($_GET['id']);

// Inclui a conexão com o banco (deve conter o objeto $pdo já configurado)
require_once '../conexao.php';

try {
    // Consulta para obter os dados do tutor
    $stmt = $pdo->prepare("SELECT * FROM Tutores WHERE id = :id");
    $stmt->execute(['id' => $id_tutor]);
    $tutor = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verifica se o tutor foi encontrado
    if (!$tutor) {
        header("Location: pesquisa_tutores.php"); // Redireciona se o tutor não for encontrado
        exit();
    }

} catch (PDOException $e) {
    echo "Erro na conexão: " . $e->getMessage();
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
        <!-- Adicione mais informações do tutor conforme necessário -->

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
