<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuário</title>
    <link rel="stylesheet" href="ASSETS/CSS/style.css">
</head>
<body>

    <header class="header">
        <img src="ASSETS/IMG/capa.png" alt="Capa do site">
    </header>

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

    <main class="main-content">
        <section class="signup-section">
            <h1>Cadastro de Usuário</h1>
            <?php if (isset($_SESSION['error'])): ?>
                <p style="color: red;"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
            <?php endif; ?>

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

                <!-- Campo Idioma -->
                <label for="idioma">Idioma:</label>
                <input type="text" id="idioma" name="idioma">
                <button type="button" onclick="adicionarIdioma()">Adicionar Idioma</button>
                <br><br>

                <!-- Lista de idiomas adicionados -->
                <ul id="lista_idiomas"></ul>
                <input type="hidden" id="idiomas_list" name="idiomas_list">

                <button type="submit">Cadastrar</button>
            </form>

            <p>Já possui uma conta? <a href="login.php">Faça login aqui</a></p>
        </section>
    </main>

    <footer class="footer">
        UNIVESP PI 2024
    </footer>

    <script>
        function adicionarIdioma() {
            var idioma = document.getElementById("idioma").value;
            if (idioma) {
                var listaIdiomas = document.getElementById("lista_idiomas");
                var itemIdioma = document.createElement("li");
                itemIdioma.textContent = idioma;
                listaIdiomas.appendChild(itemIdioma);

                var idiomasList = document.getElementById("idiomas_list").value;
                document.getElementById("idiomas_list").value = idiomasList ? idiomasList + ',' + idioma : idioma;
                
                document.getElementById("idioma").value = ""; // Limpa o campo
            }
        }
    </script>

</body>
</html>
