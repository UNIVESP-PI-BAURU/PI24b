<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="ASSETS/CSS/style.css">
</head>

<body>

    <!-- Cabeçalho -->    
    <div class="header">
        <img src="ASSETS/IMG/capa.png" alt="Imagem de Capa">
    </div>

    <!-- Navegação -->
    <nav class="navbar">
        <a href="./index.php">Home</a>
        <a href="./sobre_nos.php">Sobre nós</a>
        <a href="./cadastro.php">Cadastro</a>
    </nav>

    <!-- Mensagens de erro ou sucesso -->
    <div class="message-area">
        <?php
        session_start();
        if (isset($_SESSION['success'])) {
            echo "<div class='success'>{$_SESSION['success']}</div>";
            unset($_SESSION['success']);
        }
        if (isset($_SESSION['error'])) {
            echo "<div class='error'>{$_SESSION['error']}</div>";
            unset($_SESSION['error']);
        }
        ?>
    </div>

    <!-- Main (conteúdo) -->
    <div class="main-content">
        <div class="signup-section">
            <h2>Entrar</h2>

            <!-- Formulário de Login -->
            <form method="POST" action="proc_login.php">
                
                <!-- Tipo de Usuário -->
                <div class="user-type">
                    <div>
                        <input type="radio" id="aluno" name="tipo_usuario" value="aluno" checked>
                        <label for="aluno">Aluno</label>
                    </div>
                    <div>
                        <input type="radio" id="tutor" name="tipo_usuario" value="tutor">
                        <label for="tutor">Tutor</label>
                    </div>
                </div>

                <!-- Campos de Login -->
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="senha">Senha:</label>
                <input type="password" id="senha" name="senha" required>

                <!-- Botão de Login -->
                <button type="submit" name="login" class="login-button">Login</button>
            </form>
        </div>
    </div>

    <!-- Rodapé -->
    <div class="footer">
        UNIVESP PI 2024
    </div>

    <script>
        // Scripts adicionais, se necessário
    </script>

</body>
</html>
