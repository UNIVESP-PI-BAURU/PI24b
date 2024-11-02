<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Nova Conta</title>
    <link rel="stylesheet" href="ASSETS/CSS/style.css">
    <script>
        // Função para exibir mensagens
        function mostrarMensagem(mensagem) {
            alert(mensagem);
        }

        // Carrega os dados na inicialização da página
        window.onload = () => {
            carregarDados();
            carregarIdiomas();

            <?php session_start(); ?>
            <?php if (isset($_SESSION['message'])): ?>
                mostrarMensagem("<?= $_SESSION['message'] ?>");
                <?php unset($_SESSION['message']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                mostrarMensagem("<?= $_SESSION['error'] ?>");
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
        };
    </script>
    <script>
        // Variáveis globais para armazenar estados e municípios
        let estados = [];
        let municipios = [];

        // Função para carregar JSON de estados e municípios
        async function carregarDados() {
            try {
                const resEstados = await fetch('estados.json');
                estados = await resEstados.json();
                console.log('Estados carregados:', estados); // Debug

                const resMunicipios = await fetch('municipios.json');
                municipios = await resMunicipios.json();
                console.log('Municípios carregados:', municipios); // Debug

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

            console.log('Cidades atualizadas para o estado:', estadoSelecionado); // Debug
        }

        // Função para carregar idiomas do arquivo JSON
        let idiomas = [];

        async function carregarIdiomas() {
            try {
                const response = await fetch('idioma.json');
                idiomas = await response.json();
                console.log('Idiomas carregados:', idiomas); // Debug
            } catch (error) {
                console.error('Erro ao carregar idiomas:', error);
            }
        }

        // Função para autocomplete de idiomas
        function autocompleteIdiomas(inputIdioma, suggestions) {
            suggestions.innerHTML = ''; // Limpa as sugestões anteriores
            suggestions.style.display = 'none'; // Esconde as sugestões

            const valor = inputIdioma.value;

            if (valor.length > 1) { // Começa a mostrar sugestões a partir de 2 caracteres
                const resultados = idiomas.filter(idioma => idioma.idioma.toLowerCase().startsWith(valor.toLowerCase()));
                console.log('Resultados do autocomplete para', valor, ':', resultados); // Debug

                resultados.forEach(idioma => {
                    const li = document.createElement('li');
                    li.textContent = idioma.idioma; // Altera para idioma
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
    </script>
</head>
<body>
    <form action="proc_cadastro.php" method="POST" enctype="multipart/form-data">
        <label for="tipo_usuario">Tipo de Usuário:</label>
        <select id="tipo_usuario" name="tipo_usuario" required>
            <option value="aluno">Aluno</option>
            <option value="tutor">Tutor</option>
        </select>

        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha" required>

        <label for="estado">Estado:</label>
        <select id="estado" name="estado" onchange="atualizarCidades()" required></select>

        <label for="cidade">Cidade:</label>
        <select id="cidade" name="cidade" required></select>

        <label for="data_nascimento">Data de Nascimento:</label>
        <input type="date" id="data_nascimento" name="data_nascimento">

        <label for="biografia">Biografia:</label>
        <textarea id="biografia" name="biografia"></textarea>

        <label for="idiomas">Idiomas:</label>
        <input type="text" id="idiomas" name="idiomas" oninput="autocompleteIdiomas(this, document.getElementById('sugestoesIdiomas'))">
        <ul id="sugestoesIdiomas" style="display:none;"></ul>

        <label for="foto_perfil">Foto de Perfil:</label>
        <input type="file" id="foto_perfil" name="foto_perfil" accept="image/*">

        <button type="submit" name="registrar">Registrar</button>
    </form>
</body>
</html>
