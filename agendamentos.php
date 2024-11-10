<?php
// Conexão com o banco de dados
require_once 'conexao.php';

// Inicialização da sessão
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo_usuario'] !== 'aluno') {
    echo "Usuário não autorizado! Redirecionando para login...";
    header("Location: login.php");
    exit();
}

// Obter as aulas agendadas pelo aluno
$sql_agendamentos = "SELECT * FROM Aulas WHERE id_usuario = ? ORDER BY data_aula DESC";
$stmt = $conn->prepare($sql_agendamentos);
$stmt->bind_param("i", $_SESSION['id_usuario']);
$stmt->execute();
$result_agendamentos = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Agendamentos</title>
</head>
<body>
    <h2>Meus Agendamentos</h2>
    
    <!-- Exibição de mensagens de feedback -->
    <?php if (isset($_SESSION['msg'])): ?>
        <p><?php echo $_SESSION['msg']; ?></p>
        <?php unset($_SESSION['msg']); // Limpar a mensagem após exibir ?>
    <?php endif; ?>

    <?php if ($result_agendamentos->num_rows > 0): ?>
        <ul>
            <?php while ($aula = $result_agendamentos->fetch_assoc()): ?>
                <li>
                    <strong><?php echo htmlspecialchars($aula['titulo']); ?></strong><br>
                    Data: <?php echo htmlspecialchars($aula['data_aula']); ?><br>
                    Hora: <?php echo htmlspecialchars($aula['hora_aula']); ?><br>
                    Local: <?php echo htmlspecialchars($aula['local']); ?><br>
                    <form method="POST" action="cancelar_aula.php">
                        <input type="hidden" name="id_aula" value="<?php echo $aula['id_aula']; ?>">
                        <button type="submit">Cancelar Aula</button>
                    </form>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p>Você não tem aulas agendadas.</p>
    <?php endif; ?>
</body>
</html>
