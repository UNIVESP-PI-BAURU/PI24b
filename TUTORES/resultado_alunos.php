<?php 
session_start();

// Verifica se há erros na sessão
$erro = $_SESSION['erro_consulta'] ?? null;
unset($_SESSION['erro_consulta']); // Limpa o erro após exibição

// Verifica se há resultados armazenados na sessão
if (!isset($_SESSION['alunos_resultados'])) {
    // Redireciona para a pesquisa se não houver resultados
    header("Location: pesquisa_alunos.php");
    exit();
}

// Obtém os resultados da sessão
$resultados = $_SESSION['alunos_resultados'];

// Limpa os resultados da sessão após a exibição
unset($_SESSION['alunos_resultados']);
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
    <div class="main-result">
        <div class="result" style="padding: 1rem; background-color: #f9f9f9; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                 width: 95%; max-width: 1000px; box-sizing: border-box; margin-top: 2rem;">
            <h2 style="text-align: center; margin-bottom: 1rem;">Resultados da Pesquisa de Alunos</h2>
        
            <?php if (!empty($resultados)): ?>
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th style="border: 1px solid #ddd; padding: 4px;">Nome</th>
                            <th style="border: 1px solid #ddd; padding: 4px;">Cidade</th>
                            <th style="border: 1px solid #ddd; padding: 4px;">Estado</th>
                            <th style="border: 1px solid #ddd; padding: 4px;">Idioma</th>
                            <th style="border: 1px solid #ddd; padding: 4px;">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($resultados as $aluno): ?>
                            <tr>
                                <td style="border: 1px solid #ddd; padding: 4px;"><?php echo htmlspecialchars($aluno['nome']); ?></td>
                                <td style="border: 1px solid #ddd; padding: 4px;"><?php echo htmlspecialchars($aluno['cidade']); ?></td>
                                <td style="border: 1px solid #ddd; padding: 4px;"><?php echo htmlspecialchars($aluno['estado']); ?></td>
                                <td style="border: 1px solid #ddd; padding: 4px;"><?php echo htmlspecialchars($aluno['idiomas']); ?></td>
                                <td style="border: 1px solid #ddd; padding: 4px;"><a href="about_aluno.php?id=<?php echo htmlspecialchars($aluno['id']); ?>">Ver mais</a></td>
                            </tr>
                            <tr><td colspan="5" style="height: 4px;"></td></tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Nenhum aluno encontrado com os critérios informados.</p>
            <?php endif; ?>

        </div>
    </div>

    <!-- Rodapé -->
    <div class="footer">
        UNIVESP PI 2024
    </div>

</body>
</html>
