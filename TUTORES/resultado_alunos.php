<?php
session_start();

if (!isset($_SESSION['id_aluno']) && !isset($_SESSION['id_tutor'])) {
    header("Location: ../login.php");
    exit();
}

require_once '../conexao.php';

// Dados da sessão
$alunos_resultados = $_SESSION['alunos_resultados'] ?? null;
$erro_consulta = $_SESSION['erro_consulta'] ?? null;
$total_resultados = isset($_SESSION['total_paginas']) ? $_SESSION['total_paginas'] * 10 : 0; // Total baseado na sessão
$limite = 10;

// Calcula o total de páginas
$total_paginas = isset($_SESSION['total_paginas']) ? $_SESSION['total_paginas'] : 1;
$pagina_atual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;

// Limpa a sessão
unset($_SESSION['alunos_resultados'], $_SESSION['erro_consulta'], $_SESSION['total_paginas']);
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
    <header class="header">
        <img src="ASSETS/IMG/capa.png" alt="Imagem de Capa">
    </header>
    <nav class="navbar">
        <a href="./index.php">Home</a>
        <a href="./sobre_nos.php">Sobre nós</a>
        <a href="./cadastro.html">Cadastro</a>
    </nav>
    <div class="main-content">
        <div class="signup-section">
            <h2>Resultados da Pesquisa de Alunos</h2>

            <?php if ($erro_consulta): ?>
                <p><?php echo htmlspecialchars($erro_consulta); ?></p>
            <?php elseif ($alunos_resultados && count($alunos_resultados) > 0): ?>
                <ul>
                    <?php foreach ($alunos_resultados as $aluno): ?>
                        <li><?php echo htmlspecialchars($aluno['nome']) . " - " . 
                                   htmlspecialchars($aluno['cidade']) . ", " . 
                                   htmlspecialchars($aluno['estado']) . " (" . 
                                   htmlspecialchars($aluno['idioma']) . ")"; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <!-- Paginação -->
                <div class="pagination">
                    <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                        <a href="?pagina=<?php echo $i; ?>" 
                           class="<?php echo ($i === $pagina_atual) ? 'active' : ''; ?>">
                           <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>
                </div>

            <?php else: ?>
                <p>Desculpe, não encontramos registros com esses dados. Tente novamente.</p>
            <?php endif; ?>
        </div>
    </div>
    <footer class="footer">
        <p>UNIVESP PI 2024</p>
    </footer>
</body>
</html>
