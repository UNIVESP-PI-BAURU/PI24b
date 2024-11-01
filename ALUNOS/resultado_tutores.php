<?php
session_start();

// Verifica se há erros na sessão
$erro = $_SESSION['erro_consulta'] ?? null;
unset($_SESSION['erro_consulta']); // Limpa o erro após exibição

// Verifica se há resultados armazenados na sessão
if (!isset($_SESSION['tutores_resultados'])) {
    // Redireciona para a pesquisa se não houver resultados
    header("Location: pesquisa_tutor.php");
    exit();
}

// Obtém os resultados da sessão
$resultados = $_SESSION['tutores_resultados'];

// Limpa os resultados da sessão após a exibição
unset($_SESSION['tutores_resultados']);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados da Pesquisa</title>
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

    <!-- Mensagem de erro, se houver -->
    <?php if ($erro): ?>
        <div class="error-message">
            <p><?php echo htmlspecialchars($erro); ?></p>
        </div>
    <?php endif; ?>

    <!-- Resultados da Pesquisa -->
    <div class="main-content">
        <div class="signup-section">
            <h2>Resultados da Pesquisa de Tutores</h2>
            
            <?php if (!empty($resultados)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Cidade</th>
                            <th>Estado</th>
                            <th>Idioma</th> <!-- Adiciona a coluna de idioma -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($resultados as $tutor): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($tutor['nome']); ?></td>
                                <td><?php echo htmlspecialchars($tutor['cidade']); ?></td>
                                <td><?php echo htmlspecialchars($tutor['estado']); ?></td>
                                <td><?php echo htmlspecialchars($tutor['idiomas']); ?></td> <!-- Exibe os idiomas -->
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Nenhum tutor encontrado com os critérios informados.</p>
            <?php endif; ?>

        </div>
    </div>

    <!-- Rodapé -->
    <div class="footer">
        UNIVESP PI 2024
    </div>

</body>
</html>
