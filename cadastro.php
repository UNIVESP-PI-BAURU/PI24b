<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Nova Conta</title>
    <link rel="stylesheet" href="ASSETS/CSS/style.css">
    <script>
        // Variáveis globais para armazenar estados, municípios e idiomas
        let estados = [];
        let municipios = [];
        let idiomas = []; // Adicionando a variável para idiomas

        // Função para carregar JSON de estados, municípios e idiomas
        async function carregarDados() {
            try {
                const resEstados = await fetch('estados.json');
                estados = await resEstados.json();

                const resMunicipios = await fetch('municipios.json');
                municipios = await resMunicipios.json();

                const resIdiomas = await fetch('idioma.json'); // Carregar idiomas
                idiomas = await resIdiomas.json();

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

        // Função para autocomplete de idiomas
        function autocompleteIdiomas(inputIdioma, suggestions) {
            suggestions.innerHTML = ''; // Limpa as sugestões anteriores
            suggestions.style.display = 'none'; // Esconde as sugestões

            const valor = inputIdioma.value.toLowerCase();

            if (valor.length > 1) { // Começa a mostrar sugestões a partir de 2 caracteres
                const resultados = idiomas.filter(idioma => idioma.idioma.toLowerCase().includes(valor)); // Alterado para usar idioma.idioma
                
                resultados.forEach(idioma => {
                    const li = document.createElement('li');
                    li.textContent = idioma.idioma; // Altera para idioma.idioma
                    li.onclick = () => {
                        inputIdioma.value = idioma.idioma; // Preenche o campo com o idioma selecionado
                        suggestions.style.display = 'none'; // Esconde as sugestões
                    };
                    suggestions.appendChild(li);
                });

                if (resultados.length > 0) {
                    suggestions.style.display = 'block'; // Mostra as sugestões se houver resultados
                }
            }
        }

        // Carrega os dados assim que a página for aberta
        window.onload = carregarDados;

        // Função para adicionar novo campo de idioma
        function addCampoIdioma() {
            const divIdiomas = document.getElementById('idiomas');
            const novoCampo = document.createElement('div');
            novoCampo.innerHTML = `
                <label for="idioma">Idioma:</label>
                <input type="text" name="idiomas[]" required oninput="autocompleteIdiomas(this, this.nextElementSibling)">
                <ul style="display:none; position:absolute; background:#fff; border:1px solid #ccc; max-height:150px; overflow-y:auto; z-index:1000;"></ul>
            `; // Novo campo com autocomplete
            divIdiomas.appendChild(novoCampo);
        }
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
                <select class="user-type" id="estado" name="estado" onchange="atualizarCidades()" required>
                    <option value="">Selecione um estado</option>
                </select>

                <label for="cidade">Cidade:</label>
                <select class="user-type" id="cidade" name="cidade" required>
                    <option value="">Selecione uma cidade</option>
                </select>

                <label for="data_nascimento">Data de Nascimento:</label>
                <input type="date" id="data_nascimento" name="data_nascimento">

                <label for="biografia">Biografia:</label>
                <textarea id="biografia" name="biografia"></textarea>

                <div id="idiomas">
                    <label for="idioma">Idioma:</label>
                    <input type="text" id="idioma" name="idiomas[]" required oninput="autocompleteIdiomas(this, this.nextElementSibling)">
                    <ul style="display:none; position:absolute; background:#fff; border:1px solid #ccc; max-height:150px; overflow-y:auto; z-index:1000;"></ul> <!-- Lista de sugestões -->
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
</body>
</html>
