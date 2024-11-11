<?php
session_start();
require_once 'conexao.php';

if (!isset($_SESSION['id_usuario']) || !isset($_GET['id_conversa'])) {
    header("Location: login.php");
    exit();
}

$id_conversa = intval($_GET['id_conversa']);
$id_usuario_logado = $_SESSION['id_usuario'];

// Recupera as mensagens da conversa
$sql_mensagens = "SELECT m.*, 
                  CASE WHEN m.id_remetente = :id_usuario THEN 'Você' ELSE 'Outro' END AS remetente
                  FROM Mensagens m
                  WHERE m.id_conversa = :id_conversa
                  ORDER BY m.data_envio ASC";
$stmt_mensagens = $conn->prepare($sql_mensagens);
$stmt_mensagens->bindParam(':id_conversa', $id_conversa, PDO::PARAM_INT);
$stmt_mensagens->bindParam(':id_usuario', $id_usuario_logado, PDO::PARAM_INT);
$stmt_mensagens->execute();
$mensagens = $stmt_mensagens->fetchAll(PDO::FETCH_ASSOC);

// Envio de nova mensagem
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['mensagem'])) {
    $mensagem = trim($_POST['mensagem']);

    // Define o destinatário com base na conversa
    $sql_destinatario = "
        SELECT CASE 
                   WHEN :id_usuario = id_aluno THEN id_tutor 
                   ELSE id_aluno 
               END AS id_destinatario
        FROM Conversas 
        WHERE id_conversa = :id_conversa";
    $stmt_destinatario = $conn->prepare($sql_destinatario);
    $stmt_destinatario->bindParam(':id_usuario', $id_usuario_logado, PDO::PARAM_INT);
    $stmt_destinatario->bindParam(':id_conversa', $id_conversa, PDO::PARAM_INT);
    $stmt_destinatario->execute();
    $destinatario = $stmt_destinatario->fetch(PDO::FETCH_ASSOC);
    
    $id_destinatario = $destinatario['id_destinatario'];

    // Insere a nova mensagem
    $sql_envio = "INSERT INTO Mensagens (id_remetente, id_destinatario, id_conversa, mensagem, data_envio)
                  VALUES (:id_remetente, :id_destinatario, :id_conversa, :mensagem, NOW())";
    $stmt_envio = $conn->prepare($sql_envio);
    $stmt_envio->bindParam(':id_remetente', $id_usuario_logado, PDO::PARAM_INT);
    $stmt_envio->bindParam(':id_destinatario', $id_destinatario, PDO::PARAM_INT);
    $stmt_envio->bindParam(':id_conversa', $id_conversa, PDO::PARAM_INT);
    $stmt_envio->bindParam(':mensagem', $mensagem, PDO::PARAM_STR);
    $stmt_envio->execute();

    header("Location: conversa.php?id_conversa=$id_conversa");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Conversa</title>
    <style>
        #mensagens {
            max-height: 400px;
            overflow-y: auto;
            border: 1px solid #ddd;
            padding: 10px;
        }
        .mensagem {
            margin-bottom: 10px;
        }
        .remetente {
            font-weight: bold;
        }
        .data-envio {
            font-size: 0.8em;
            color: #888;
        }
    </style>
</head>
<body>
    <h1>Conversa</h1>

    <!-- Exibindo as mensagens -->
    <div id="mensagens">
        <?php foreach ($mensagens as $mensagem): ?>
            <div class="mensagem">
                <span class="remetente"><?php echo htmlspecialchars($mensagem['remetente']); ?>:</span>
                <span><?php echo htmlspecialchars($mensagem['mensagem']); ?></span>
                <br>
                <span class="data-envio"><?php echo htmlspecialchars($mensagem['data_envio']); ?></span>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Formulário para enviar uma nova mensagem -->
    <form method="post" action="">
        <textarea name="mensagem" rows="3" cols="50" placeholder="Digite sua mensagem"></textarea>
        <button type="submit">Enviar</button>
    </form>

    <script>
        // Função para atualizar as mensagens automaticamente
        setInterval(() => {
            fetch(`conversa.php?id_conversa=<?php echo $id_conversa; ?>&update=1`)
                .then(response => response.text())
                .then(data => {
                    document.querySelector('#mensagens').innerHTML = data;
                });
        }, 3000); // Atualiza a cada 3 segundos
    </script>
</body>
</html>
