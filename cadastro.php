<?php
session_start();
?>

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

            <!-- Formulário de Cadastro -->
            <form method="POST" action="proc_cadastro.php">
                
                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="senha">Senha:</label>
                <input type="password" id="senha" name="senha" required>

                <label for="tipo_usuario">Tipo de Usuário:</label>
                <div>
                    <input type="radio" id="aluno" name="tipo_usuario" value="aluno" checked>
                    <label for="aluno">Aluno</label>
                </div>
                <div>
                    <input type="radio" id="tutor" name="tipo_usuario" value="tutor">
                    <label for="tutor">Tutor</label>
                </div>

                <!-- Idiomas -->
                <label for="idioma">Idioma:</label>
                <input type="text" id="idioma" name="idioma" required placeholder="Digite o idioma">

                <button type="submit" name="cadastrar">Cadastrar</button>
            </form>

        </section>
    </main>

    <!-- Rodapé -->
    <footer class="footer">
        <p>UNIVESP PI 2024</p>
    </footer>

</body>
</html>
