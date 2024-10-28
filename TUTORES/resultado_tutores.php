<?php
session_start();

// Verifica se o usuário está logado e redireciona para login se não estiver
if (!isset($_SESSION['id_aluno']) && !isset($_SESSION['id_tutor'])) {
    header("Location: ../login.php");
    exit();
}

require_once '../conexao.php'; // Inclui a conexão com o banco

// Verifica se existem resultados armazenados na sessão
$tutores_resultados = isset($_SESSION['tutores_resultados']) ? $_SESSION['tutores_resultados'] : null;
$erro_consulta = isset($_SESSION['erro_consulta']) ? $_SESSION['erro_consulta'] : null;

// Limpa a sessão após a leitura
unset($_SESSION['tutores_resultados']);
unset($_SESSION['erro_consulta']);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados da Pesquisa de Tutores</title>
    <link rel="stylesheet" href="ASSETS/CSS/style.css">
</head>
<body>

    <!-- Cabeçalho -->
    <header class="header">
        <img src="ASSETS/IMG/capa.png" alt="Imagem de Capa">
    </header>

    <!-- Navegação -->
    <nav class="navbar">
        <a href="./index.php">Home</a>
        <a href="./sobre_nos.php">Sobre nós</a>
        <a href="./cadastro.html">Cadastro</a>
    </nav>

    <!-- Conteúdo Principal -->
    <div class="main-content">

        <div class="signup-section">
            <h2>Resultados da Pesquisa</h2>
        
            <?php if ($erro_consulta): ?>
                <p><?php echo htmlspecialchars($erro_consulta); ?></p>
            <?php elseif ($tutores_resultados && $tutores_resultados->num_rows > 0): ?>
                <ul>
                    <?php while ($row = $tutores_resultados->fetch_assoc()): ?>
                        <li><?php echo htmlspecialchars($row['nome']) . " - " . htmlspecialchars($row['cidade']) . ", " . htmlspecialchars($row['estado']) . " (" . htmlspecialchars($row['idioma']) . ")"; ?></li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <p>Desculpe, não localizamos registros com estes dados. Favor tentar novamente.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Rodapé -->
    <footer class="footer">
        <p>UNIVESP PI 2024</p>
    </footer>

</body>
</html>
