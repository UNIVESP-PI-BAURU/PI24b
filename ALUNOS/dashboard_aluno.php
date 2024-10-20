<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Conectando Interesses</title>
    <link rel="stylesheet" href="../ASSETS/CSS/style.css"> <!-- Atualizado para caminho correto -->
</head>

<body>

    <?php
    // Iniciar a sessão
    session_start();

    // Verifica se o usuário está logado; se não, redireciona para a página de login
    if (!isset($_SESSION['id_aluno']) && !isset($_SESSION['id_tutor'])) {
        header("Location: ../login.php");
        exit();
    }

    // Inclui o arquivo de conexão com o banco de dados
    require_once '../conexao.php'; // Atualizado para caminho correto

    // Define o tipo de usuário e busca os dados do usuário
    $tipo_usuario = isset($_SESSION['id_aluno']) ? 'aluno' : 'tutor';
    $id_usuario = $_SESSION['id_' . $tipo_usuario];
    $tabela_usuario = ($tipo_usuario == 'aluno') ? 'Alunos' : 'Tutores';

    // Busca os dados do usuário
    $sql = "SELECT nome, foto_perfil, cidade, estado FROM $tabela_usuario WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id_usuario);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verifica se os dados do usuário foram encontrados
    if (!$usuario) {
        echo "<p>Usuário não encontrado.</p>";
        exit();
    }
    ?>

    <!-- Cabeçalho -->
    <header class="header">
        <img src="../ASSETS/IMG/capa.png" alt="Capa do Site"> <!-- Atualizado para caminho correto -->
    </header>
    <!-- fim Cabeçalho -->

    <!-- Navegação -->
    <nav class="navbar">
        <a href="../index.php">Home</a>
        <a href="../sobre_nos.php">Sobre nós</a>
        <a href="../login.php">Login</a> <!-- Aqui pode ser alterado para "Logoff" se o usuário estiver logado -->
        <a href="./dashboard_aluno.php">Dashboard</a>
    </nav>
    <!-- fim Navegação -->

    <!-- Conteúdo Principal -->
    <main class="main-content">
        <section class="dashboard-section">

            <!-- saudacao -->
            <div class="saudacao">
                <h1>Bem-vindo, <?php echo htmlspecialchars($usuario['nome']); ?>!</h1>
            </div>
            <!-- fim saudacao -->

            <!-- perfil -->
            <div class="perfil">
                <div style="display: flex; align-items: center; margin-bottom: 10px;">
                    <div style="flex: 1;">
                        <?php if (!empty($usuario['foto_perfil'])): ?>
                            <img src="<?php echo htmlspecialchars($usuario['foto_perfil']); ?>" alt="Avatar" style="width: 80px; height: 80px; border-radius: 50%;">
                        <?php else: ?>
                            <p>Sem foto</p>
                        <?php endif; ?>
                    </div>
                    <div style="flex: 2; padding-left: 10px;">
                        <p><?php echo ($tipo_usuario == "tutor" ? "Tutor(a): " : "Aluno(a): ") . htmlspecialchars($usuario['nome']); ?></p>
                        <?php if (!empty($usuario['cidade']) || !empty($usuario['estado'])): ?>
                            <p>
                                <?php echo htmlspecialchars($usuario['cidade']) ? htmlspecialchars($usuario['cidade']) . ', ' : ''; ?>
                                <?php echo htmlspecialchars($usuario['estado']) ? htmlspecialchars($usuario['estado']) : ''; ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
                <button onclick="window.location.href='perfil.php'">Ver meu perfil</button>
            </div>
            <!-- fim perfil -->

            <!-- search -->
            <div class="search">
                <input type="text" placeholder="Pesquise por tutores..." />
                <button>Pesquisar</button>
            </div>
            <!-- fim search -->

            <!-- aulas -->
            <div class="aulas">
                <h2>Aulas em andamento:</h2>
                <!-- Listar aulas aqui -->
            </div>
            <!-- fim aulas -->

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
