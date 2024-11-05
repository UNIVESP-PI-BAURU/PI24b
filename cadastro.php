<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Conectando Interesses</title>
    <link rel="stylesheet" href="ASSETS/CSS/style.css">
</head>
<body>

    <!-- Cabeçalho -->
    <header class="header">
        <img src="ASSETS/IMG/capa.png" alt="Capa do Site">
    </header>

    <!-- Navegação -->
    <nav class="navbar">
        <a href="index.php">Home</a>
        <a href="sobre_nos.php">Sobre nós</a>
        <a href="login.php">Login</a>
    </nav>
    <!-- Fim da Navegação -->

    <!-- Conteúdo Principal -->
    <main class="main-content">
        <section class="signup-section">
            <h2>Cadastro</h2>

            <?php
            // Mensagem de erro ou sucesso
            if (isset($_SESSION['error'])) {
                echo '<p style="color: red;">' . htmlspecialchars($_SESSION['error']) . '</p>';
                unset($_SESSION['error']);
            }

            if (isset($_GET['success'])) {
                echo '<p style="color: green;">' . htmlspecialchars($_GET['success']) . '</p>';
            }
            ?>

            <form action="proc_cadastro.php" method="POST">
                <input type="text" name="nome" placeholder="Seu nome" required>
                <input type="email" name="email" placeholder="Seu email" required>
                <input type="password" name="senha" placeholder="Sua senha" required>
                <select name="tipo_usuario" required>
                    <option value="aluno">Aluno</option>
                    <option value="tutor">Tutor</option>
                </select>
                <button type="submit">Cadastrar</button>
            </form>
        </section>
    </main>

    <!-- Rodapé -->
    <footer class="footer">
        <p>UNIVESP PI 2024</p>
    </footer>

</body>
</html>
