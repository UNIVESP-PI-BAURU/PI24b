<?php
// Conexão com o banco de dados
require_once 'conexao.php';

// Inicialização da sessão
session_start();

// Verifica se o usuário está logado e é tutor
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo_usuario'] !== 'tutor') {
    echo "Usuário não autorizado! Redirecionando para login...";
    header("Location: login.php");
    exit();
}

// Processar a atualização de disponibilidade
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_tutor = $_SESSION['id_usuario'];
    $dia = $_POST['dia'];
    $hora_inicio = $_POST['hora_inicio'];
    $hora_fim = $_POST['hora_fim'];

    // Inserir disponibilidade no banco de dados
    $sql_disponibilidade = "INSERT INTO Disponibilidade_Tutores (id_tutor, dia, hora_inicio, hora_fim) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql_disponibilidade);
    $stmt->bind_param("isss", $id_tutor, $dia, $hora_inicio, $hora_fim);
    $stmt->execute();

    // Mensagem de feedback
    $_SESSION['msg'] = "Disponibilidade atualizada com sucesso!";
    header("Location: editar_disponibilidade.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Disponibilidade</title>
</head>
<body>
    <h2>Editar Disponibilidade</h2>

    <!-- Exibição de mensagens de feedback -->
    <?php if (isset($_SESSION['msg'])): ?>
        <p><?php echo $_SESSION['msg']; ?></p>
        <?php unset($_SESSION['msg']); // Limpar a mensagem após exibir ?>
    <?php endif; ?>

    <form method="POST" action="editar_disponibilidade.php">
        <label for="dia">Dia da Semana:</label>
        <select name="dia" id="dia" required>
            <option value="segunda">Segunda-feira</option>
            <option value="terça">Terça-feira</option>
            <option value="quarta">Quarta-feira</option>
            <option value="quinta">Quinta-feira</option>
            <option value="sexta">Sexta-feira</option>
            <option value="sábado">Sábado</option>
            <option value="domingo">Domingo</option>
        </select>
        <br>

        <label for="hora_inicio">Hora de Início:</label>
        <input type="time" name="hora_inicio" required>
        <br>

        <label for="hora_fim">Hora de Fim:</label>
        <input type="time" name="hora_fim" required>
        <br>

        <button type="submit">Salvar Disponibilidade</button>
    </form>
</body>
</html>
