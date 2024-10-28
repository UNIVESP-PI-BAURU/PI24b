<?php
session_start();

// Verifica se o usuário está logado e redireciona para login se não estiver
if (!isset($_SESSION['id_aluno']) && !isset($_SESSION['id_tutor'])) {
    header("Location: ../login.php");
    exit();
}

require_once '../conexao.php'; // Inclui a conexão com o banco de dados

// Verifica se existem resultados armazenados na sessão
$alunos_resultados = isset($_SESSION['alunos_resultados']) ? $_SESSION['alunos_resultados'] : null;
$erro_consulta = isset($_SESSION['erro_consulta']) ? $_SESSION['erro_consulta'] : null;

// Limpa os dados da sessão após a leitura
unset($_SESSION['alunos_resultados']);
unset($_SESSION['erro_consulta']);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados da Pesquisa de Alunos</title>
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
            <h2>Resultados da Pesquisa de Alunos</h2>

            <?php if ($erro_consulta): ?>
                <p><?php echo htmlspecialchars($erro_consulta); ?></p>
            <?php elseif ($alunos_resultados && count($alunos_resultados) > 0): ?>
                <ul>
                    <?php foreach ($alunos_resultados as $aluno): ?>
                        <li>
                            <?php echo htmlspecialchars($aluno['nome']) . " - " . 
                                       htmlspecialchars($aluno['cidade']) . ", " . 
                                       htmlspecialchars($aluno['estado']) . 
                                       " (" . htmlspecialchars($aluno['idioma']) . ")"; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Desculpe, não encontramos registros com esses dados. Por favor, tente novamente.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Rodapé -->
    <footer class="footer">
        <p>UNIVESP PI 2024</p>
    </footer>

</body>
</html>
