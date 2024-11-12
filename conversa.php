<?php
session_start();
require_once 'conexao.php';

if (!isset($_SESSION['id_usuario']) || !isset($_GET['id_conversa'])) {
    header("Location: login.php");
    exit();
}

$id_conversa = intval($_GET['id_conversa']);
$id_usuario_logado = $_SESSION['id_usuario'];

// Atualizar o status das mensagens para "lida" ao acessar a conversa
$sql_atualiza_status = "UPDATE Mensagens 
                        SET status_leitura = 'lida' 
                        WHERE id_conversa = :id_conversa 
                          AND id_destinatario = :id_usuario 
                          AND status_leitura = 'não_lida'"; 
$stmt_atualiza_status = $conn->prepare($sql_atualiza_status);
$stmt_atualiza_status->bindParam(':id_conversa', $id_conversa, PDO::PARAM_INT);
$stmt_atualiza_status->bindParam(':id_usuario', $id_usuario_logado, PDO::PARAM_INT);
$stmt_atualiza_status->execute();

// Recupera as mensagens da conversa
$sql_mensagens = "SELECT m.id, m.mensagem AS conteudo, m.data_envio, m.status_leitura, 
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

// Se o parâmetro "update" estiver presente, retorna apenas as mensagens
if (isset($_GET['update'])) {
    foreach ($mensagens as $mensagem) {
        echo "<div class='mensagem'>";
        echo "<span class='remetente'>" . htmlspecialchars($mensagem['remetente']) . ":</span>";
        echo "<span>" . htmlspecialchars($mensagem['conteudo']) . "</span><br>";
        echo "<span class='data-envio'>" . htmlspecialchars($mensagem['data_envio']) . "</span>";
        if ($mensagem['status_leitura'] == 'não_lida') {
            echo "<span class='status-leitura'>(Não lida)</span>";
        } else {
            echo "<span class='status-leitura'>(Lida)</span>";
        }
        echo "</div>";
    }
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
            .status-leitura {
                font-size: 0.9em;
                color: green;
            }
        </style>
    </head>
    <body>

        <header class="header">
            <img src="ASSETS/IMG/capa.png" alt="Imagem de Capa">
        </header>

        <nav class="navbar">
            <button onclick="window.location.href='index.php'">Home</button>
            <button onclick="window.location.href='dashboard.php'">Meu painel</button>
            <button onclick="window.location.href='logout.php'">Logout</button>
        </nav>

        <!-- Conteúdo Principal -->
        <?php include 'conversa_main.php'; ?>

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

        <footer class="footer">
            <p>UNIVESP PI 2024</p>
            <p><a href="https://github.com/UNIVESP-PI-BAURU/PI24b.git" target="_blank">https://github.com/UNIVESP-PI-BAURU/PI24b.git</a></p>
        </footer>

    </body>
</html>
