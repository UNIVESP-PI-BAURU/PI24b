<?php 
session_start();

// Verifica se há erros na sessão
$erro = $_SESSION['erro_consulta'] ?? null;
unset($_SESSION['erro_consulta']); // Limpa o erro após exibição

// Verifica se há resultados armazenados na sessão
if (!isset($_SESSION['resultados_alunos'])) {
    // Redireciona para a pesquisa se não houver resultados
    header("Location: pesquisa_alunos.php");
    exit();
}

// Obtém os resultados da sessão
$resultados = $_SESSION['resultados_alunos'];

// Limpa os resultados da sessão após a exibição
unset($_SESSION['resultados_alunos']);

// Presumindo que a ID do tutor está armazenada na sessão
$id_tutor = $_SESSION['id_tutor'] ?? null;

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
        <a href="../index.php">Home</a>
        <a href="../sobre_nos.php">Sobre nós</a>
        <a href="<?php echo isset($_SESSION['id_tutor']) ? './dashboard_tutor.php' : './dashboard_aluno.php'; ?>">Dashboard</a>
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
            <h2>Resultados da Pesquisa por Alunos</h2>
        
            <?php if (!empty($resultados)): ?>
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th style="border: 1px solid #ddd; padding: 4px;">ID do Aluno</th>
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
                        <?php foreach ($resultados as $aluno): ?>
                            <tr>
                                <td style="border: 1px solid #ddd; padding: 4px;"><?php echo htmlspecialchars($aluno['id_aluno']); ?></td>
                                <td style="border: 1px solid #ddd; padding: 4px;"><?php echo htmlspecialchars($aluno['nome']); ?></td>
                                <td style="border: 1px solid #ddd; padding: 4px;"><?php echo htmlspecialchars($aluno['cidade']); ?></td>
                                <td style="border: 1px solid #ddd; padding: 4px;"><?php echo htmlspecialchars($aluno['estado']); ?></td>
                                <td style="border: 1px solid #ddd; padding: 4px;"><?php echo htmlspecialchars($aluno['idiomas']); ?></td>
                                <td style="border: 1px solid #ddd; padding: 4px;"><?php echo htmlspecialchars($aluno['tipo_usuario']); ?></td>
                                <td style="border: 1px solid #ddd; padding: 4px;"><?php echo htmlspecialchars($aluno['tipo_conversor']); ?></td>
                                <td style="border: 1px solid #ddd; padding: 4px;">
                                    <a href="about_aluno.php?id=<?php echo isset($aluno['id_aluno']) ? htmlspecialchars($aluno['id_aluno']) : ''; ?>">Ver mais</a>
                                    <!-- Campo oculto para a ID do aluno -->
                                    <input type="hidden" name="id_aluno[]" value="<?php echo isset($aluno['id_aluno']) ? htmlspecialchars($aluno['id_aluno']) : ''; ?>" />
                                    <!-- Campo oculto para a ID do tutor -->
                                    <input type="hidden" name="id_tutor" value="<?php echo htmlspecialchars($id_tutor); ?>" />
                                </td>
                            </tr>
                            <tr><td colspan="8" style="height: 4px;"></td></tr> <!-- Espaço entre resultados -->
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Nenhum aluno encontrado com os critérios informados.</p>
            <?php endif; ?>
            <br>
            <div class="actions" style="text-align: center; margin: 20px 0;">
                <button onclick="window.location.href='pesquisa_alunos.php'" class="custom-button">Voltar para Pesquisa de Alunos</button>
            </div>
        </div>
    </div>

    <!-- Rodapé -->
    <div class="footer">
        UNIVESP PI 2024
    </div>

</body>
</html>
