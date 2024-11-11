<?php
session_start();
require_once 'conexao.php';

// Verifica se o usuário está logado e se o ID do destinatário foi fornecido
if (!isset($_SESSION['id_usuario']) || !isset($_GET['id'])) {
    header("Location: login.php");
    exit();
}

$id_usuario_logado = $_SESSION['id_usuario'];
$id_destinatario = $_GET['id'];

// Verifica se o destinatário é aluno ou tutor
$tabela_destinatario = $_SESSION['tipo_usuario'] === 'aluno' ? 'Tutores' : 'Alunos';

// Verifica se já existe uma conversa entre o usuário logado e o destinatário
$sql_conversa = "SELECT id_conversa FROM Mensagens WHERE (id_remetente = :id_usuario_logado AND id_destinatario = :id_destinatario)
                 OR (id_remetente = :id_destinatario AND id_destinatario = :id_usuario_logado)
                 LIMIT 1";
$stmt_conversa = $conn->prepare($sql_conversa);
$stmt_conversa->bindParam(':id_usuario_logado', $id_usuario_logado, PDO::PARAM_INT);
$stmt_conversa->bindParam(':id_destinatario', $id_destinatario, PDO::PARAM_INT);
$stmt_conversa->execute();
$conversa = $stmt_conversa->fetch(PDO::FETCH_ASSOC);

// Se não existe conversa, cria uma nova
if (!$conversa) {
    $sql_criar_conversa = "INSERT INTO Mensagens (id_remetente, id_destinatario, mensagem, data_envio)
                           VALUES (:id_usuario_logado, :id_destinatario, 'Conversa iniciada', NOW())";
    $stmt_criar_conversa = $conn->prepare($sql_criar_conversa);
    $stmt_criar_conversa->bindParam(':id_usuario_logado', $id_usuario_logado, PDO::PARAM_INT);
    $stmt_criar_conversa->bindParam(':id_destinatario', $id_destinatario, PDO::PARAM_INT);
    $stmt_criar_conversa->execute();

    // Recupera o id da nova conversa
    $id_conversa = $conn->lastInsertId();
} else {
    $id_conversa = $conversa['id_conversa'];
}

// Carrega as mensagens da conversa
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
    header("Location: conversa.php?id=$id_destinatario");
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
    <h2>Conversa com <?php echo htmlspecialchars($_SESSION['tipo_usuario'] === 'aluno' ? 'Tutor' : 'Aluno'); ?></h2>

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
