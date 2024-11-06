<?php
session_start(); // Inicia a sessão

// Verifica se o usuário já está logado e redireciona para dashboard
if (isset($_SESSION['id']) && isset($_SESSION['tipo'])) {
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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

        <?php if (isset($_SESSION['id'])): ?>
            <!-- Usuário logado -->
            <a href="./logout.php">Logout</a>
        <?php else: ?>
            <!-- Usuário não logado -->
            <a href="./login.php">Login</a>
            <a href="./cadastro.php">Cadastro</a>
        <?php endif; ?>
    </nav>

    <!-- Conteúdo Principal -->
    <main class="main-content">
        <section class="signup-section">
            <h1>Login</h1>

            <!-- Exibe mensagem de erro, se houver -->
            <?php if (isset($_SESSION['login_error'])): ?>
                <p style="color: red;"><?php echo $_SESSION['login_error']; unset($_SESSION['login_error']); ?></p>
            <?php endif; ?>

            <!-- Formulário de Login -->
            <form action="proc_login.php" method="POST">
                <label for="tipo_usuario">Tipo de Usuário:</label>
                <select id="tipo_usuario" name="tipo_usuario" required>
                    <option value="aluno">Aluno</option>
                    <option value="tutor">Tutor</option>
                </select>
                <br><br>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                <br><br>

                <label for="senha">Senha:</label>
                <input type="password" id="senha" name="senha" required>
                <br><br>

                <button type="submit">Entrar</button>
            </form>

            <p>Não possui uma conta? <a href="cadastro.php">Cadastre-se aqui</a></p>

         </section>
    </main>

    <!-- Rodapé -->
    <footer class="footer">
        UNIVESP PI 2024
    </footer>

</body>
</html>
