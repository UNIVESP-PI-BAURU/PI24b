<?php 
session_start();

// Verifica se há erros na sessão
$erro = $_SESSION['erro_consulta'] ?? null;
unset($_SESSION['erro_consulta']); // Limpa o erro após exibição

// Verifica se há resultados armazenados na sessão
if (!isset($_SESSION['tutores_resultados'])) {
    // Redireciona para a pesquisa se não houver resultados
    header("Location: pesquisa_tutores.php");
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
        <div class="result" style="padding: 1rem; background-color: #f9f9f9; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); width: 95%; max-width: 500px; box-sizing: border-box; margin-top: 2rem;">
            <h2 style="text-align: center; margin-bottom: 1rem;">Resultados da Pesquisa de Tutores</h2>
        
            <?php if (!empty($resultados)): ?>
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th style="border: 1px solid #ddd; padding: 8px;">Nome</th>
                            <th style="border: 1px solid #ddd; padding: 8px;">Cidade</th>
                            <th style="border: 1px solid #ddd; padding: 8px;">Estado</th>
                            <th style="border: 1px solid #ddd; padding: 8px;">Idioma</th>
                            <th style="border: 1px solid #ddd; padding: 8px;">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($resultados as $tutor): ?>
                            <tr>
                                <td style="border: 1px solid #ddd; padding: 8px;"><?php echo htmlspecialchars($tutor['nome']); ?></td>
                                <td style="border: 1px solid #ddd; padding: 8px;"><?php echo htmlspecialchars($tutor['cidade']); ?></td>
                                <td style="border: 1px solid #ddd; padding: 8px;"><?php echo htmlspecialchars($tutor['estado']); ?></td>
                                <td style="border: 1px solid #ddd; padding: 8px;"><?php echo htmlspecialchars($tutor['idiomas']); ?></td>
                                <td style="border: 1px solid #ddd; padding: 8px;"><a href="about.php?id=<?php echo htmlspecialchars($tutor['id']); ?>">Ver mais</a></td>
                            </tr>
                            <tr><td colspan="5" style="height: 10px;"></td></tr> <!-- Espaço entre resultados -->
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
