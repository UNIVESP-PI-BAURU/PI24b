<?php 
session_start();

// Verifica se o ID do aluno foi passado na URL
if (!isset($_GET['id'])) {
    // Redireciona para a página de pesquisa se o ID não for fornecido
    header("Location: pesquisa_alunos.php");
    exit();
}

// Obtém o ID do aluno da URL
$id_aluno = intval($_GET['id']);

// Inclui a conexão com o banco (deve conter o objeto $pdo já configurado)
require_once '../conexao.php';

try {
    // Consulta para obter os dados do aluno
    $stmt = $pdo->prepare("SELECT * FROM Alunos WHERE id = :id");
    $stmt->execute(['id' => $id_aluno]);
    $aluno = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verifica se o aluno foi encontrado
    if (!$aluno) {
        header("Location: pesquisa_alunos.php"); // Redireciona se o aluno não for encontrado
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
        <!-- Adicione mais informações do aluno conforme necessário -->

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
