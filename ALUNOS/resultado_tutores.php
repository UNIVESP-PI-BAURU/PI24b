<?php 
session_start(); // Inicia a sessão

// Verifica se o tutor está logado
if (!isset($_SESSION['id_tutor'])) {
    error_log("Tutor não logado, redirecionando para login.");
    header("Location: ../login.php");
    exit();
}

// Verifica se há erros na sessão
$erro = $_SESSION['erro_consulta'] ?? null;
unset($_SESSION['erro_consulta']); // Limpa o erro após exibição

// Verifica se há resultados armazenados na sessão
if (!isset($_SESSION['resultados_tutores'])) {
    // Redireciona para a pesquisa se não houver resultados
    header("Location: pesquisa_tutores.php");
    exit();
}

// Obtém os resultados da sessão
$resultados = $_SESSION['resultados_tutores'];

// Limpa os resultados da sessão após a exibição
unset($_SESSION['resultados_tutores']);

// Debug: Exibir resultados recebidos
error_log("Resultados recebidos: " . print_r($resultados, true));
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados da Pesquisa</title>
    <link rel="stylesheet" href="../ASSETS/CSS/style.css">
</head>
<body>

    <!-- Cabeçalho -->
    <div class="header">
        <img src="../ASSETS/IMG/capa.png" alt="Imagem de Capa">
    </div>

    <!-- Navegação -->
    <nav class="navbar">
        <a href="../index.php">Home</a>
        <a href="../sobre_nos.php">Sobre nós</a>
        <a href="./dashboard_tutor.php">Dashboard</a>
        <a href="../logout.php">Logout</a>
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
            <h2>Resultados da Pesquisa por Tutores</h2>
        
            <?php if (!empty($resultados)): ?>
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th style="border: 1px solid #ddd; padding: 4px;">ID do Tutor</th>
                            <th style="border: 1px solid #ddd; padding: 4px;">Nome</th>
                            <th style="border: 1px solid #ddd; padding: 4px;">Cidade</th>
                            <th style="border: 1px solid #ddd; padding: 4px;">Estado</th>
                            <th style="border: 1px solid #ddd; padding: 4px;">Idioma</th>
                            <th style="border: 1px solid #ddd; padding: 4px;">Tipo Usuário</th>
                            <th style="border: 1px solid #ddd; padding: 4px;">Tipo Conversor</th>
                            <th style="border: 1px solid #ddd; padding: 4px;">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($resultados as $tutor): ?>
                            <tr>
                                <td style="border: 1px solid #ddd; padding: 4px;"><?php echo htmlspecialchars($tutor['id_tutor']); ?></td>
                                <td style="border: 1px solid #ddd; padding: 4px;"><?php echo htmlspecialchars($tutor['nome']); ?></td>
                                <td style="border: 1px solid #ddd; padding: 4px;"><?php echo htmlspecialchars($tutor['cidade']); ?></td>
                                <td style="border: 1px solid #ddd; padding: 4px;"><?php echo htmlspecialchars($tutor['estado']); ?></td>
                                <td style="border: 1px solid #ddd; padding: 4px;"><?php echo htmlspecialchars($tutor['idiomas']); ?></td>
                                <td style="border: 1px solid #ddd; padding: 4px;"><?php echo htmlspecialchars($tutor['tipo_usuario']); ?></td>
                                <td style="border: 1px solid #ddd; padding: 4px;"><?php echo htmlspecialchars($tutor['tipo_conversor']); ?></td>
                                <td style="border: 1px solid #ddd; padding: 4px;">
                                    <a href="about_tutor.php?id=<?php echo isset($tutor['id_tutor']) ? htmlspecialchars($tutor['id_tutor']) : ''; ?>">Ver mais</a>
                                </td>
                            </tr>
                            <tr><td colspan="8" style="height: 4px;"></td></tr> <!-- Espaço entre resultados -->
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Nenhum tutor encontrado com os critérios informados.</p>
            <?php endif; ?>
            <br>
            <div class="actions" style="text-align: center; margin: 20px 0;">
                <button onclick="window.location.href='pesquisa_tutores.php'" class="custom-button">Voltar para Pesquisa de Tutores</button>
            </div>
        </div>
    </div>

    <!-- Rodapé -->
    <div class="footer">
        UNIVESP PI 2024
    </div>

</body>
</html>
