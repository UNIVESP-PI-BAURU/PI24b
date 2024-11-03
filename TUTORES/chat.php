<?php
session_start();
if (!isset($_SESSION['id_tutor']) && !isset($_SESSION['id_aluno'])) {
    header("Location: login.php");
    exit();
}

// Conexão com o banco de dados
require 'conexao.php';

// Obtém o ID e o tipo do usuário com quem está conversando
$id_conversor = $_GET['id'] ?? null;
$tipo_conversor = $_GET['tipo_conversor'] ?? null;

// ID e tipo do usuário atual
$id_usuario = $_SESSION['id_tutor'] ?? $_SESSION['id_aluno'];
$tipo_usuario = isset($_SESSION['id_tutor']) ? 'tutor' : 'aluno';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>
    <link rel="stylesheet" href="ASSETS/CSS/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

    <div class="header">
        <h2>Chat</h2>
    </div>

    <div class="chat-container">
        <div class="messages" id="messages">
            <!-- Aqui você irá carregar as mensagens do banco de dados -->
        </div>

        <textarea id="message" placeholder="Digite sua mensagem aqui..."></textarea>
        <button id="send-message">Enviar</button>
    </div>

    <script>
        $(document).ready(function() {
            function loadMessages() {
                $.ajax({
                    url: 'load_messages.php',
                    type: 'GET',
                    data: { 
                        id: '<?php echo $id_conversor; ?>', 
                        tipo_conversor: '<?php echo $tipo_conversor; ?>' 
                    },
                    success: function(data) {
                        $('#messages').html(data);
                    }
                });
            }

            loadMessages(); // Carregar mensagens ao iniciar

            $('#send-message').on('click', function() {
                var message = $('#message').val();
                if (message) {
                    $.ajax({
                        url: 'send_message.php',
                        type: 'POST',
                        data: {
                            id: '<?php echo $id_conversor; ?>',
                            tipo_conversor: '<?php echo $tipo_conversor; ?>',
                            message: message
                        },
                        success: function() {
                            $('#message').val(''); // Limpar textarea
                            loadMessages(); // Recarregar mensagens
                        }
                    });
                }
            });

            // Atualiza mensagens a cada 5 segundos
            setInterval(loadMessages, 5000);
        });
    </script>

    <div class="footer">
        UNIVESP PI 2024
    </div>
</body>
</html>
