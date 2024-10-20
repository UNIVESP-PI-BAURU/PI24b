<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Conectando Interesses</title>
    <link rel="stylesheet" href="ASSETS/CSS/style.css">
</head>

<body>

    <!-- Cabeçalho -->
    <header class="header">
        <img src="ASSETS/IMG/capa.png">
    </header>

    <!-- Navegação -->
    <nav class="navbar">
        <a href="./index.php">Home</a>
        <a href="./sobre_nos.php">Sobre nós</a>
        <a href="./professores.php">Professores</a>
        <a href="./alunos.php">Alunos</a>
        <a href="./login.php">Login</a>
        <a href="./dashboard_aluno.php">Dashboard</a>
    </nav>

    <!-- Conteúdo Principal -->
    <main class="main-content">
        <section class="dashboard-section">
            <h1>Bem-vindo, <?php echo $_SESSION['nome_aluno']; ?>!</h1>
            <p>Aqui estão suas informações e atividades recentes.</p>
            <!-- Exiba outras informações relevantes, como progresso ou links úteis -->
            <div class="activity">
                <h2>Atividades Recentes</h2>
                <ul>
                    <li>Atividade 1</li>
                    <li>Atividade 2</li>
                    <li>Atividade 3</li>
                </ul>
            </div>
            <a href="./alunos.php" class="signup-button">Voltar para Alunos</a>
        </section>
    </main>

    <!-- Rodapé -->
    <footer class="footer">
        <p>UNIVESP PI 2024</p>
    </footer>

</body>

</html>
