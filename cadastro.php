<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Nova Conta</title>
    <link rel="stylesheet" href="ASSETS/CSS/style.css">
</head>
<body>

    <!-- Cabeçalho -->    
    <div class="header">
        <img src="ASSETS/IMG/capa.png" alt="Imagem de Capa">
    </div>

    <!-- Navegação -->
    <nav class="navbar">
        <a href="./index.php">Home</a>
        <a href="./sobre_nos.php">Sobre nós</a>
        <a href="./login.php">Login</a>
    </nav>

    <!-- Main (conteúdo) -->
    <div class="main-content">
        <div class="signup-section">
            <h2>Cadastre-se</h2>

            <form action="proc_cadastro.php" method="POST" enctype="multipart/form-data">
                <label>Tipo de Usuário:</label>
                <div>
                    <input type="radio" id="aluno" name="tipo_usuario" value="aluno" required>
                    <label for="aluno">Aluno</label>
                </div>
                <div>
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
        </div>
    </div>

    <script>
        function mostrarMensagem(mensagem) {
            alert(mensagem);
        }

        window.onload = () => {
            carregarDados();
            carregarIdiomas();

            <?php if (isset($_SESSION['message'])): ?>
                mostrarMensagem("<?= $_SESSION['message'] ?>");
                <?php unset($_SESSION['message']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                mostrarMensagem("<?= $_SESSION['error'] ?>");
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
        };

        let estados = [];
        let municipios = [];

        async function carregarDados() {
            try {
                const resEstados = await fetch('estados.json');
                estados = await resEstados.json();
                console.log('Estados carregados:', estados);

                const resMunicipios = await fetch('municipios.json');
                municipios = await resMunicipios.json();
                console.log('Municípios carregados:', municipios);

                preencherEstados();
            } catch (error) {
                console.error('Erro ao carregar dados:', error);
            }
        }

        function preencherEstados() {
            const estadoSelect = document.getElementById('estado');
            estados.forEach(estado => {
                const option = document.createElement('option');
                option.value = estado.sigla;
                option.textContent = estado.nome;
                estadoSelect.appendChild(option);
            });
        }

        function atualizarCidades() {
            const estadoSelecionado = document.getElementById('estado').value;
            const cidadeSelect = document.getElementById('cidade');
            cidadeSelect.innerHTML = '';

            const cidadesFiltradas = municipios.filter(m => m.microrregiao.mesorregiao.UF.sigla === estadoSelecionado);

            cidadesFiltradas.forEach(cidade => {
                const option = document.createElement('option');
                option.value = cidade.nome;
                option.textContent = cidade.nome;
                cidadeSelect.appendChild(option);
            });

            console.log('Cidades atualizadas para o estado:', estadoSelecionado);
        }

        let idiomas = [];

        async function carregarIdiomas() {
            try {
                const response = await fetch('idioma.json');
                idiomas = await response.json();
                console.log('Idiomas carregados:', idiomas);
            } catch (error) {
                console.error('Erro ao carregar idiomas:', error);
            }
        }

        function autocompleteIdiomas(inputIdioma, suggestions) {
            suggestions.innerHTML = '';
            suggestions.style.display = 'none';

            const valor = inputIdioma.value;

            if (valor.length > 1) {
                const resultados = idiomas.filter(idioma => idioma.idioma.toLowerCase().startsWith(valor.toLowerCase()));
                console.log('Resultados do autocomplete para', valor, ':', resultados);

                resultados.forEach(idioma => {
                    const li = document.createElement('li');
                    li.textContent = idioma.idioma;
                    li.onclick = () => {
                        inputIdioma.value = idioma.idioma;
                        suggestions.style.display = 'none';
                    };
                    suggestions.appendChild(li);
                });

                if (resultados.length > 0) {
                    suggestions.style.display = 'block';
                }
            }
        }
    </script>

    <!-- Rodapé -->
    <div class="footer">
        UNIVESP PI 2024
    </div>

</body>
</html>
