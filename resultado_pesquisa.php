<?php 
session_start(); // Inicia a sessão

// Verifica se o usuário está logado
if (!isset($_SESSION['id']) || !isset($_SESSION['tipo'])) {
    error_log("Usuário não logado, redirecionando para login.");
    header("Location: login.php");
    exit();
}

// Verifica se há erros na sessão
$erro = $_SESSION['erro_consulta'] ?? null;
unset($_SESSION['erro_consulta']); // Limpa o erro após exibição

// Verifica se há resultados armazenados na sessão
if (!isset($_SESSION['resultados'])) {
    // Redireciona para a pesquisa se não houver resultados
    header("Location: pesquisa.php");  // Atualizado para a pesquisa única
    exit();
}

// Obtém os resultados da sessão
$resultados = $_SESSION['resultados'];

// Limpa os resultados da sessão após a exibição
unset($_SESSION['resultados']);

// Debug: Exibir resultados recebidos
error_log("Resultados recebidos: " . print_r($resultados, true));
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
        <a href="index.php">Home</a>
        <a href="sobre_nos.php">Sobre nós</a>
        <a href="dashboard.php">Dashboard</a> <!-- Link ajustado para a dashboard única -->
        <a href="logout.php">Logout</a>
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
            <h2>Resultados da Pesquisa</h2>
        
            <?php if (!empty($resultados)): ?>
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th style="border: 1px solid #ddd; padding: 4px;">ID</th>
                            <th style="border: 1px solid #ddd; padding: 4px;">Nome</th>
                            <th style="border: 1px solid #ddd; padding: 4px;">Cidade</th>
                            <th style="border: 1px solid #ddd; padding: 4px;">Estado</th>
                            <th style="border: 1px solid #ddd; padding: 4px;">Idioma(s)</th>
                            <th style="border: 1px solid #ddd; padding: 4px;">Tipo Usuário</th>
                            <th style="border: 1px solid #ddd; padding: 4px;">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($resultados as $usuario): ?>
                            <tr>
                                <td style="border: 1px solid #ddd; padding: 4px;"><?php echo htmlspecialchars($usuario['id']); ?></td>
                                <td style="border: 1px solid #ddd; padding: 4px;"><?php echo htmlspecialchars($usuario['nome']); ?></td>
                                <td style="border: 1px solid #ddd; padding: 4px;"><?php echo htmlspecialchars($usuario['cidade']); ?></td>
                                <td style="border: 1px solid #ddd; padding: 4px;"><?php echo htmlspecialchars($usuario['estado']); ?></td>
                                <td style="border: 1px solid #ddd; padding: 4px;"><?php echo htmlspecialchars($usuario['idiomas']); ?></td>
                                <td style="border: 1px solid #ddd; padding: 4px;"><?php echo htmlspecialchars($usuario['tipo_usuario']); ?></td>
                                <td style="border: 1px solid #ddd; padding: 4px;">
                                    <a href="about_usuario.php?id=<?php echo isset($usuario['id']) ? htmlspecialchars($usuario['id']) : ''; ?>">Ver mais</a>
                                </td>
                            </tr>
                            <tr><td colspan="7" style="height: 4px;"></td></tr> <!-- Espaço entre resultados -->
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Nenhum resultado encontrado com os critérios informados.</p>
            <?php endif; ?>
            <br>
            <div class="actions" style="text-align: center; margin: 20px 0;">
                <button onclick="window.location.href='pesquisa.php'" class="custom-button">Voltar para Pesquisa</button>
            </div>
        </div>
    </div>

    <!-- Rodapé -->
    <div class="footer">
        UNIVESP PI 2024
    </div>

</body>
</html>
