<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobre Nós - Conectando Interesses</title>
    <link rel="stylesheet" href="ASSETS/CSS/style.css">
</head>

<body>

    <!-- Cabeçalho -->
    <header class="header">
        <!-- Debug: Verificar se a imagem está carregando -->
        <?php 
            if (file_exists('ASSETS/IMG/capa.png')) {
                echo '<img src="ASSETS/IMG/capa.png" alt="Imagem de Capa">';
            } else {
                error_log("Erro: Imagem 'capa.png' não encontrada.");
            }
        ?>
    </header>

    <!-- Navegação -->
    <nav class="navbar">
        <a href="./index.php">Home</a>
        <!-- Debug: Verificar Sessão -->
        <?php
        session_start(); // Inicia a sessão para verificar o login
        error_log("Sessão: " . print_r($_SESSION, true)); // Exibe conteúdo da sessão para debug

        if (isset($_SESSION['id_aluno']) || isset($_SESSION['id_tutor'])): ?>
            <!-- Usuário logado -->
            <a href="./logout.php">Logout</a>
        <?php else: ?>
            <!-- Usuário não logado -->
            <a href="./login.php">Login</a>
            <a href="./cadastro.php">Cadastro</a>
        <?php endif; ?>
    </nav>
    <!-- Fim Navegação -->

    <!-- Conteúdo Principal -->
    <main class="main-content">
        <section class="signup-section">
            <h2>Sobre Nós</h2>
            <p>
                A C.I. (Conectando Interesses) surgiu em 2024, como um projeto acadêmico.
                Unindo ideias, valores e objetivos em comum, a Conectando Interesses tem a proposta
                de ampliar os horizontes linguísticos e culturais, quebrando a barreira geográfica entre professores ou nativos 
                de línguas estrangeiras e potenciais alunos.
            </p>
            <p>
                Nosso objetivo é proporcionar aos usuários uma experiência agradável na plataforma,
                dando espaço para a escolha dos próprios tutores e horários de aprendizado.
            </p>
            <p>
                Junte-se a nós e encontre com facilidade professores das mais variadas línguas.
            </p>
            <p>
                <strong>Seja você também um aluno ou tutor C.I!</strong>
            </p>
        </section>
    </main>

    <!-- Rodapé -->
    <footer class="footer">
        UNIVESP PI 2024
    </footer>

</body>

</html>
