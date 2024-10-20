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

    // Verifica se o usuário está logado; se não, redireciona para a página de login
    if (!isset($_SESSION['id_aluno']) && !isset($_SESSION['id_tutor'])) {
        header("Location: ../login.php");
        exit();
    }
    ?>

    <!-- Cabeçalho -->
    <header class="header">
        <img src="ASSETS/IMG/capa.png" alt="Capa do Site">
    </header>
    <!-- fim Cabeçalho -->

    <!-- Navegação -->
    <nav class="navbar">
        <a href="./index.php">Home</a>
        <a href="./sobre_nos.php">Sobre nós</a>
        <a href="./login.php">Login</a> <!-- Aqui pode ser alterado para "Logoff" se o usuário estiver logado -->
        <a href="./dashboard_aluno.php">Dashboard</a>
    </nav>
    <!-- fim Navegação -->

    <!-- Conteúdo Principal -->
    <main class="main-content">
        <section class="dashboard-section">

            <!-- saudacao -->
            <div class="saudacao">
                <h1>Bem-vindo, <?php echo isset($_SESSION['nome']) ? $_SESSION['nome'] : 'Visitante'; ?>!</h1>
            </div>
            <!-- fim saudacao -->

            <!-- perfil -->
            <div class="perfil">
                <div style="display: flex; align-items: center; margin-bottom: 10px;">
                    <div style="flex: 1;">
                        <?php if (!empty($usuario['foto_perfil'])): ?>
                            <img src="<?php echo $usuario['foto_perfil']; ?>" alt="Avatar" style="width: 80px; height: 80px; border-radius: 50%;">
                        <?php else: ?>
                            <p>Sem foto</p>
                        <?php endif; ?>
                    </div>
                    <div style="flex: 2; padding-left: 10px;">
                        <p><?php echo $tipo_usuario == "tutor" ? "Tutor(a): " : "Aluno(a): "; ?><?php echo $usuario['nome']; ?></p>
                        <?php if (!empty($usuario['cidade']) || !empty($usuario['estado'])): ?>
                            <p>
                                <?php echo $usuario['cidade'] ? $usuario['cidade'] . ', ' : ''; ?>
                                <?php echo $usuario['estado'] ? $usuario['estado'] : ''; ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
                <button onclick="window.location.href='perfil.php'">Ver meu perfil</button>
            </div>
            <!-- fim perfil -->


            <!-- search -->
            <div class="search">
                <!-- aqui o sistema mais importante: onde o aluno pesquisará um tutor ou um tutor pesquisará por alunos -->
                <input type="text" placeholder="Pesquise por tutores..." />
                <button>Pesquisar</button>
            </div>
            <!-- fim search -->

            <!-- aulas -->
            <div class="aulas">
                <!-- aqui exibirá as aulas/cursos em andamento no momento -->
                <h2>Aulas em andamento:</h2>
                <!-- Listar aulas aqui -->
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
