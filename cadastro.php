<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuário</title>
    <link rel="stylesheet" href="ASSETS/CSS/style.css">
    <script>
        // Função para adicionar um novo campo de idioma
        function adicionarIdioma() {
            var container = document.getElementById('idiomas-container');
            var novoCampo = document.createElement('div');
            novoCampo.classList.add('idioma-container');

            // Adiciona o campo de entrada para o idioma
            novoCampo.innerHTML = `
                <label for="idioma[]">Idioma:</label>
                <input type="text" name="idioma[]" required>
                <br><br>
            `;
            container.appendChild(novoCampo);
        }
    </script>
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
        session_start(); // Inicia a sessão para verificar o login

        if (isset($_SESSION['id'])): // Verifica se o usuário já está logado
            // Usuário logado
            header("Location: dashboard.php");
            exit();
        else: ?>
            <!-- Usuário não logado -->
            <a href="./login.php">Login</a>
            <a href="./cadastro.php">Cadastro</a>
        <?php endif; ?>
    </nav>
    <!-- fim Navegação -->

    <!-- Conteúdo Principal -->
    <main class="main-content">
        <section class="signup-section">
            <h1>Cadastro de Usuário</h1>

            <!-- Exibe mensagem de erro, se houver -->
            <?php if (isset($_SESSION['error'])): ?>
                <p style="color: red;"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
            <?php endif; ?>

            <!-- Formulário de Cadastro -->
            <form action="proc_cadastro.php" method="POST">
                <!-- Seleção do Tipo de Usuário -->
                <label for="tipo_usuario">Tipo de Usuário:</label>
                <select id="tipo_usuario" name="tipo_usuario" required>
                    <option value="aluno">Aluno</option>
                    <option value="tutor">Tutor</option>
                </select>
                <br><br>

                <!-- Campo Nome -->
                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome" required>
                <br><br>

                <!-- Campo Email -->
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                <br><br>

                <!-- Campo Senha -->
                <label for="senha">Senha:</label>
                <input type="password" id="senha" name="senha" required>
                <br><br>

                <!-- Container de Idiomas -->
                <div id="idiomas-container">
                    <label for="idioma[]">Idioma:</label>
                    <input type="text" name="idioma[]" required>
                    <br><br>
                </div>

                <!-- Botão para adicionar mais idiomas -->
                <button type="button" onclick="adicionarIdioma()">Adicionar Outro Idioma</button>
                <br><br>

                <!-- Botão de Enviar -->
                <button type="submit">Cadastrar</button>
            </form>

            <p>Já possui uma conta? <a href="login.php">Faça login aqui</a></p>

        </section>
    </main>

    <!-- Rodapé -->
    <footer class="footer">
        UNIVESP PI 2024
    </footer>

</body>
</html>
