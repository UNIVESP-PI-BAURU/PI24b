<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Conectando Interesses</title>
    <link rel="stylesheet" href="ASSETS/CSS/style.css">
</head>

<body>

    <!-- Iniciar a sessão -->
    <?php
    session_start();
    ?>

    <!-- Cabeçalho -->
    <header class="header">
        <img src="ASSETS/IMG/capa.png">
    </header>
    <!-- fim Cabeçalho -->

    <!-- Navegação -->
    <nav class="navbar">
        <a href="./index.php">Home</a>
        <a href="./sobre_nos.php">Sobre nós</a>
        <a href="./login.php">Login</a> <!-- se estiver logado, aqui será logoff-->
        <a href="./dashboard_aluno.php">Dashboard</a>
    </nav>
    <!-- fim Navegação -->

    <!-- Conteúdo Principal -->
    <main class="main-content">
        <section class="dashboard-section">

            <!-- saudacao -->
            <div class="saudacao">
                <h1>Bem-vindo, <?php echo isset($_SESSION['nome_aluno']) ? $_SESSION['nome_aluno'] : 'Visitante'; ?>!</h1>
            </div>
            <!-- fim saudacao -->

            <!-- perfil -->
            <div class="perfil">
                <!-- aqui terá um botão para exibir o perfil completo -->
            </div>
            <!-- fim perfil -->

            <!-- search -->
            <div class="search">
                <!-- aqui o sistema mais importante: onde o aluno pesquisará um tutor ou um tutor pesquisará por alunos -->
            </div>
            <!-- fim search -->

            <!-- aulas -->
            <div class="aulas">
                <!-- aqui exibirá as aulas/cursos em andamento no momento -->
            </div>
            <!-- fim aulas -->

            <!-- com o tempo poderá ser adicionado ou removido mais complementos aqui -->

        </section>
    </main>
    <!-- fim Conteúdo Principal -->

    <!-- Rodapé -->
    <footer class="footer">
        <p>UNIVESP PI 2024</p>
    </footer>
    <!-- fim Rodapé -->

</body>

</html>
