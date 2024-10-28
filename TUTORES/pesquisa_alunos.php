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
            <form class="signup-form" method="POST" action="proc_pesquisa_alunos.php">
                <h3>Cidades:</h3>
                <?php
                // Recuperar cidades únicas do banco de dados
                $cidades = $conn->query("SELECT DISTINCT cidade FROM Alunos")->fetchAll(PDO::FETCH_ASSOC);
                foreach ($cidades as $cidade) {
                    echo '<label><input type="checkbox" name="cidades[]" value="' . htmlspecialchars($cidade['cidade']) . '">' . htmlspecialchars($cidade['cidade']) . '</label><br>';
                }
                ?>

                <h3>Estados:</h3>
                <?php
                // Recuperar estados únicos do banco de dados
                $estados = $conn->query("SELECT DISTINCT estado FROM Alunos")->fetchAll(PDO::FETCH_ASSOC);
                foreach ($estados as $estado) {
                    echo '<label><input type="checkbox" name="estados[]" value="' . htmlspecialchars($estado['estado']) . '">' . htmlspecialchars($estado['estado']) . '</label><br>';
                }
                ?>

                <h3>Idiomas:</h3>
                <?php
                // Recuperar idiomas únicos do banco de dados
                $idiomas = $conn->query("SELECT DISTINCT idioma FROM IdiomaAluno")->fetchAll(PDO::FETCH_ASSOC);
                foreach ($idiomas as $idioma) {
                    echo '<label><input type="checkbox" name="idiomas[]" value="' . htmlspecialchars($idioma['idioma']) . '">' . htmlspecialchars($idioma['idioma']) . '</label><br>';
                }
                ?>

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
