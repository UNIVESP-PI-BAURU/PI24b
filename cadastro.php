<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Nova Conta</title>
    <link rel="stylesheet" href="ASSETS/CSS/style.css">
</head>

<body>

    <!-- cabeçalho -->    
    <div class="header">
        <img src="ASSETS/IMG/capa.png" alt="Imagem de Capa">
    </div>

    <!-- Navegação -->
    <nav class="navbar">
        <a href="./index.php">Home</a>
        <a href="./sobre_nos.php">Sobre nós</a>
        <a href="./login.php">Login</a>
    </nav>
    <!-- fim Navegação -->

    <!-- main (conteúdo) -->
    <div class="main-content">

        <div class="signup-section">
            <h2>Criar Nova Conta</h2>
            <br>

            <!-- início form cadastro -->
            <form class="signup-form" action="proc_cadastro.php" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">

                <!-- início escolher tipo usuário -->
                <label for="tipo_usuario">Eu sou:</label>
                    <div class="user-type">
                        <div>
                            <input type="radio" id="aluno" name="tipo_usuario" value="2" required>
                            <label for="aluno">Aluno</label>
                        </div>
                        <div>
                            <input type="radio" id="tutor" name="tipo_usuario" value="1" required>
                            <label for="tutor">Tutor</label>
                        </div>
                    </div>                
                <!-- fim escolher tipo usuário -->

                <!-- início campos -->
                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="senha">Senha:</label>
                <input type="password" id="senha" name="senha" required>

                <label for="cidade">Cidade:</label>
                <input type="text" id="cidade" name="cidade">

                <label for="estado">Estado:</label>
                <input type="text" id="estado" name="estado">

                <label for="data_nascimento">Data de Nascimento:</label>
                <input type="date" id="data_nascimento" name="data_nascimento">

                <label for="biografia">Biografia:</label>
                <textarea id="biografia" name="biografia"></textarea>

                <!-- início idiomas -->
                <br>
                <div id="idiomas">
                    <label for="idioma">Idioma:</label>
                    <input type="text" id="idioma" name="idiomas[]" required>
                    <button type="button" onclick="addCampoIdioma()">Adicionar mais um</button>
                </div>
                <!-- fim idiomas -->

                <!-- início foto -->
                <br>
                <div>
                    <label for="foto_perfil">Foto de Perfil:</label>
                    <input type="file" id="foto_perfil" name="foto_perfil" accept="image/*">
                </div>
                <!-- fim foto -->

                <!-- fim campos -->

                <!-- botão submeter -->
                <button type="submit" name="registrar">Registrar</button>

            </form>
            <!-- fim form cadastro -->
        </div>

    </div>

    <!-- rodapé -->
    <div class="footer">
        UNIVESP PI 2024
    </div>

    <!-- scripts -->
    <script>
        // adicionar campo idioma
        function addCampoIdioma() {
            var divIdiomas = document.getElementById('idiomas');
            var novoCampo = document.createElement('div');
            novoCampo.innerHTML = '<label for="idioma">Idioma:</label>' +
                                  '<input type="text" id="idioma" name="idiomas[]" required>';
            divIdiomas.appendChild(novoCampo);
        }
    </script>

</body>
</html>
