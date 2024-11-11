<?php
session_start();
require_once 'conexao.php';

// Verifica se o usuário está logado e se o ID do destinatário foi fornecido
if (!isset($_SESSION['id_usuario']) || !isset($_GET['id_conversa'])) {
    header("Location: login.php");
    exit();
}

$id_usuario_logado = $_SESSION['id_usuario'];
$id_conversa = $_GET['id_conversa'];

// Carrega as mensagens entre o usuário logado e o destinatário
$sql_mensagens = "SELECT m.*, u1.nome AS remetente_nome, u2.nome AS destinatario_nome
                  FROM Mensagens m
                  JOIN Alunos u1 ON m.id_remetente = u1.id
                  JOIN Tutores u2 ON m.id_destinatario = u2.id
                  WHERE m.id_conversa = :id_conversa
                  ORDER BY m.data_envio ASC";
$stmt_mensagens = $conn->prepare($sql_mensagens);
$stmt_mensagens->bindParam(':id_conversa', $id_conversa, PDO::PARAM_INT);
$stmt_mensagens->execute();
$mensagens = $stmt_mensagens->fetchAll(PDO::FETCH_ASSOC);

// Processa o envio de uma nova mensagem
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['mensagem'])) {
    $mensagem = trim($_POST['mensagem']);

    // Insere a nova mensagem na tabela Mensagens
    $sql_envio = "INSERT INTO Mensagens (id_remetente, id_destinatario, mensagem, data_envio, id_conversa)
                  VALUES (:id_remetente, :id_destinatario, :mensagem, NOW(), :id_conversa)";
    $stmt_envio = $conn->prepare($sql_envio);
    $stmt_envio->bindParam(':id_remetente', $id_usuario_logado, PDO::PARAM_INT);
    $stmt_envio->bindParam(':id_destinatario', $id_destinatario, PDO::PARAM_INT);
    $stmt_envio->bindParam(':mensagem', $mensagem, PDO::PARAM_STR);
    $stmt_envio->bindParam(':id_conversa', $id_conversa, PDO::PARAM_INT);
    $stmt_envio->execute();

    // Atualiza a página para mostrar a nova mensagem
    header("Location: conversa.php?id_conversa=$id_conversa");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conversa</title>
    <link rel="stylesheet" href="ASSETS/CSS/style.css">
</head>
<body>

<header class="header">
    <img src="ASSETS/IMG/capa.png" alt="Capa do site">
</header>

<main class="main-content">
    <h2>Conversa</h2>

    <!-- Exibição das mensagens -->
    <div class="chat-box">
        <?php foreach ($mensagens as $mensagem): ?>
            <div class="mensagem">
                <strong><?php echo htmlspecialchars($mensagem['remetente_nome']); ?>:</strong>
                <p><?php echo htmlspecialchars($mensagem['mensagem']); ?></p>
                <span class="data-envio"><?php echo date('d/m/Y H:i', strtotime($mensagem['data_envio'])); ?></span>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Formulário para enviar mensagem -->
    <form action="" method="post" class="enviar-mensagem-form">
        <textarea name="mensagem" rows="3" placeholder="Digite sua mensagem"></textarea>
        <button type="submit">Enviar</button>
    </form>

    <button onclick="window.location.href='dashboard.php'">Voltar para a Dashboard</button>
</main>

</body>
</html>
