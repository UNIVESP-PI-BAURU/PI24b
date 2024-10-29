<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Nova Conta</title>
    <link rel="stylesheet" href="ASSETS/CSS/style.css">
    <script>
        // Variáveis globais para armazenar estados e cidades
        let estados = [];
        let municipios = [];

        // Função para carregar JSON de estados e municípios
        async function carregarDados() {
            try {
                const resEstados = await fetch('estados.json');
                estados = await resEstados.json();

                const resMunicipios = await fetch('municipios.json');
                municipios = await resMunicipios.json();

                preencherEstados();
            } catch (error) {
                console.error('Erro ao carregar dados:', error);
            }
        }

        // Preenche o dropdown de estados
        function preencherEstados() {
            const estadoSelect = document.getElementById('estado');
            estados.forEach(estado => {
                const option = document.createElement('option');
                option.value = estado.sigla;
                option.textContent = estado.nome;
                estadoSelect.appendChild(option);
            });
        }

        // Atualiza o dropdown de cidades baseado no estado selecionado
        function atualizarCidades() {
            const estadoSelecionado = document.getElementById('estado').value;
            const cidadeSelect = document.getElementById('cidade');
            cidadeSelect.innerHTML = ''; // Limpa as opções anteriores

            const cidadesFiltradas = municipios.filter(m => m.microrregiao.mesorregiao.UF.sigla === estadoSelecionado);

            cidadesFiltradas.forEach(cidade => {
                const option = document.createElement('option');
                option.value = cidade.nome;
                option.textContent = cidade.nome;
                cidadeSelect.appendChild(option);
            });
        }

        // Carrega os dados assim que a página for aberta
        window.onload = carregarDados;
    </script>
</head>

<body>
    <div class="header">
        <img src="ASSETS/IMG/capa.png" alt="Imagem de Capa">
    </div>

    <nav class="navbar">
        <a href="./index.php">Home</a>
        <a href="./sobre_nos.php">Sobre nós</a>
        <a href="./login.php">Login</a>
    </nav>

    <div class="main-content">
        <div class="signup-section">
            <h2>Criar Nova Conta</h2>

            <form class="signup-form" action="proc_cadastro.php" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
                <label for="tipo_usuario">Eu sou:</label>
                <div class="user-type">
                    <input type="radio" id="aluno" name="tipo_usuario" value="aluno" required>
                    <label for="aluno">Aluno</label>
                    <input type="radio" id="tutor" name="tipo_usuario" value="tutor" required>
                    <label for="tutor">Tutor</label>
                </div>

                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="senha">Senha:</label>
                <input type="password" id="senha" name="senha" required>

                <label for="estado">Estado:</label>
                <select id="estado" name="estado" onchange="atualizarCidades()" required>
                    <option value="">Selecione um estado</option>
                </select>

                <label for="cidade">Cidade:</label>
                <select id="cidade" name="cidade" required>
                    <option value="">Selecione uma cidade</option>
                </select>

                <label for="data_nascimento">Data de Nascimento:</label>
                <input type="date" id="data_nascimento" name="data_nascimento">

                <label for="biografia">Biografia:</label>
                <textarea id="biografia" name="biografia"></textarea>

                <div id="idiomas">
                    <label for="idioma">Idioma:</label>
                    <input type="text" id="idioma" name="idiomas[]" required>
                    <button type="button" onclick="addCampoIdioma()">Adicionar mais um</button>
                </div>

                <label for="foto_perfil">Foto de Perfil:</label>
                <input type="file" id="foto_perfil" name="foto_perfil" accept="image/*">

                <button type="submit" name="registrar">Registrar</button>
            </form>
        </div>
    </div>

    <div class="footer">
        UNIVESP PI 2024
    </div>

    <script>
        function addCampoIdioma() {
            const divIdiomas = document.getElementById('idiomas');
            const novoCampo = document.createElement('div');
            novoCampo.innerHTML = '<label for="idioma">Idioma:</label>' +
                                  '<input type="text" name="idiomas[]" required>';
            divIdiomas.appendChild(novoCampo);
        }
    </script>
</body>
</html>
