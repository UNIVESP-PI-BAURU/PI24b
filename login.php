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
        <a href="./cadastro.html">Cadastro</a>
    </nav>
    <!-- fim Navegação -->

    <!-- Mensagens de erro ou sucesso -->
    <div class="message-area">
        <?php
        session_start();
        if (isset($_SESSION['success'])) {
            echo "<div class='success'>{$_SESSION['success']}</div>";
            unset($_SESSION['success']); // Limpa a mensagem após exibir
        }
        if (isset($_SESSION['error'])) {
            echo "<div class='error'>{$_SESSION['error']}</div>";
            unset($_SESSION['error']); // Limpa a mensagem após exibir
        }
        ?>
    </div>

    <!-- Main (conteúdo) -->
    <div class="main-content">
        <div class="login-section">
            <h2>Entrar</h2>
            
            <!-- Início form login -->
            <form method="POST" action="proc_login.php">

                <!-- Início escolher tipo de usuário -->
                <div>
                    <input type="radio" id="aluno" name="tipo_usuario" value="aluno" checked>
                    <label for="aluno">Aluno</label>
                    <br>
                    <input type="radio" id="tutor" name="tipo_usuario" value="tutor">
                    <label for="tutor">Tutor</label>
                </div>
                <!-- Fim escolher tipo de usuário -->
                <br>
                <!-- Início campos -->
                <div>
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                    <br><br>
                    <label for="senha">Senha:</label>
                    <input type="password" id="senha" name="senha" required>
                </div>
                <!-- Fim campos -->
                <br>
                <!-- Botão submeter -->
                <button type="submit" name="login">Login</button>
            </form>
            <!-- Fim form login -->
        </div>
    </div>

    <!-- Rodapé -->
    <div class="footer">
        UNIVESP PI 2024
    </div>

    <!-- Scripts -->
    <script>
        // Scripts adicionais, se necessário
    </script>

</body>
</html>
