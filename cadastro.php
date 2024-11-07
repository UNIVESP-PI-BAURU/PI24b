<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuário</title>
    <link rel="stylesheet" href="ASSETS/CSS/style.css">
</head>
<body>

    <!-- Cabeçalho -->
    <header class="header">
        <img src="ASSETS/IMG/capa.png" alt="Capa do site">
    </header>

    <!-- Navegação -->
    <nav class="navbar">
        <a href="./index.php">Home</a>
        <a href="./sobre_nos.php">Sobre nós</a>

        <?php
        session_start();

        if (isset($_SESSION['id'])):
            header("Location: dashboard.php");
            exit();
        else: ?>
            <a href="./login.php">Login</a>
            <a href="./cadastro.php">Cadastro</a>
        <?php endif; ?>
    </nav>

    <!-- Conteúdo Principal -->
    <main class="main-content">
        <section class="signup-section">
            <h1>Cadastro de Usuário</h1>

            <?php if (isset($_SESSION['error'])): ?>
                <p style="color: red;"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
            <?php endif; ?>

            <!-- Formulário de Cadastro -->
            <form action="proc_cadastro.php" method="POST">
                <label for="tipo_usuario">Tipo de Usuário:</label>
                <select id="tipo_usuario" name="tipo_usuario" required>
                    <option value="aluno">Aluno</option>
                    <option value="tutor">Tutor</option>
                </select>
                <br><br>

                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome" required>
                <br><br>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                <br><br>

                <label for="senha">Senha:</label>
                <input type="password" id="senha" name="senha" required>
                <br><br>

                <label for="idioma">Idioma:</label>
                <input type="text" id="idioma" name="idioma" required>
                <br><br>

                <button type="submit">Cadastrar</button>
            </form>

            <p>Já possui uma conta? <a href="login.php">Faça login aqui</a></p>
        </section>
    </main>

    <footer class="footer">
        UNIVESP PI 2024
    </footer>

</body>
</html>
