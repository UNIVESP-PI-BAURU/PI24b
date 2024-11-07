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
            <form action="proc_cadastro.php" method="POST" onsubmit="prepareIdiomas()">
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

                <!-- Campo Idioma -->
                <label for="idiomas">Idioma:</label>
                <input type="text" id="idiomas">
                <button type="button" onclick="adicionarIdioma()">Adicionar Idioma</button>
                <br><br>

                <!-- Campo oculto para armazenar a lista de idiomas -->
                <input type="hidden" id="idiomas_list" name="idiomas_list">
                
                <!-- Área para exibir idiomas adicionados -->
                <div id="idiomas_adicionados"></div>
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

    <script>
        // Array para armazenar os idiomas
        let idiomasArray = [];

        function adicionarIdioma() {
            let idiomaInput = document.getElementById("idiomas");
            let idioma = idiomaInput.value.trim();

            if (idioma) {
                idiomasArray.push(idioma);
                idiomaInput.value = ""; // Limpa o campo de idioma

                // Atualiza o campo oculto com a lista de idiomas
                document.getElementById("idiomas_list").value = idiomasArray.join(", ");
                
                // Atualiza a exibição dos idiomas adicionados
                atualizarIdiomasAdicionados();
            }
        }

        function atualizarIdiomasAdicionados() {
            let idiomasDiv = document.getElementById("idiomas_adicionados");
            idiomasDiv.innerHTML = ""; // Limpa a lista atual
            
            idiomasArray.forEach(function(idioma, index) {
                let idiomaElement = document.createElement("p");
                idiomaElement.textContent = idioma;
                idiomasDiv.appendChild(idiomaElement);
            });
        }

        function prepareIdiomas() {
            // Confirma que o campo oculto está atualizado antes do envio
            document.getElementById("idiomas_list").value = idiomasArray.join(", ");
        }
    </script>

</body>
</html>
