<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesquisa de Alunos</title>
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
        <a href="./dashboard.php">Dashboard</a>
    </nav>
    <!-- fim Navegação -->

    <!-- Pesquisa de Alunos -->
    <div class="main-content">
        <div class="signup-section">
            <h2>Pesquisar Alunos</h2>
            <form class="signup-form" method="POST" action="resultado_alunos.php">
                <input type="text" name="cidade" placeholder="Cidade..." />
                <br>
                <input type="text" name="estado" placeholder="Estado..." />
                <br>
                <input type="text" name="idioma" placeholder="Idioma..." />
                <br>
                <button type="submit">Pesquisar</button>
            </form>
        </div>
    </div>

    <!-- Rodapé -->
    <div class="footer">
        UNIVESP PI 2024
    </div>

</body>
</html>
