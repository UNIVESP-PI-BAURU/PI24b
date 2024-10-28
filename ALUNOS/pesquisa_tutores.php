<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesquisa de Tutores</title>
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

    <!-- Pesquisa de Tutores -->
    <div class="signup-section" style="margin-top: 20px;">
        <h3>Pesquisar Tutores</h3>
        <form method="POST" action="resultado_pesquisa.php">
            <input type="text" name="cidade" placeholder="Cidade..." />
            <input type="text" name="estado" placeholder="Estado..." />
            <input type="text" name="idioma" placeholder="Idioma..." />
            <button type="submit">Pesquisar</button>
        </form>
    </div>

    <!-- Rodapé -->
    <div class="footer">
        UNIVESP PI 2024
    </div>

</body>
</html>
