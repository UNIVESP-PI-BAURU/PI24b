<?php
session_start(); // Inicia a sessão

// Verifica se o usuário está logado e redireciona para login se não estiver
if (!isset($_SESSION['id_usuario']) || !isset($_SESSION['tipo_usuario'])) {
    header("Location: login.php"); // Redireciona para login
    exit(); // Evita que o código continue
}

// Define o tipo de usuário e carrega o nome do usuário da sessão
$tipo_usuario = $_SESSION['tipo_usuario']; // 'aluno' ou 'tutor'
$nome_usuario = $_SESSION['nome_usuario'] ?? 'Visitante'; // Nome do usuário ou "Visitante" se não estiver definido
$foto_usuario = $_SESSION['foto_usuario'] ?? ''; // Foto do usuário (se existir)
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="ASSETS/CSS/style.css">
</head>
<body>

    <!-- Cabeçalho -->
    <header class="header">
        <img src="ASSETS/IMG/capa.png" alt="Capa do site">
    </header>

    <!-- Navegação -->
    <nav class="navbar">
        <button onclick="window.location.href='index.php';">Home</button>
        <button onclick="window.location.href='sobre_nos.php';">Sobre nós</button>
        <button onclick="window.location.href='dashboard.php';">Dashboard</button>
        <button onclick="window.location.href='logout.php';">Logout</button>
    </nav>
    <!-- Fim Navegação -->

    <!-- Conteúdo Principal -->
    <main class="main-content">

        <!-- complemento: Saudação -->
        <section class="signup-section">
            <h3>Bem-vindo(a), <?php echo htmlspecialchars($nome_usuario); ?>! Você é um(a) <?php echo ($tipo_usuario === 'aluno' ? 'Aluno(a)' : 'Tutor(a)'); ?>.</h3>
        </section>
        <!-- Fim Saudação -->

        <!-- complemento: Resumo do Perfil -->
        <section class="signup-section">
            <h3>Resumo do Perfil</h3>
            <div class="foto-moldura-dashboard">
                <?php if ($foto_usuario): ?>
                    <img src="ASSETS/IMG/<?php echo htmlspecialchars($foto_usuario); ?>" alt="Foto de Perfil" class="avatar-dashboard">
                <?php else: ?>
                    <span>Sem foto cadastrada</span>
                <?php endif; ?>
            </div>
            <p>Nome: <?php echo htmlspecialchars($nome_usuario); ?></p>
            <p>Tipo de usuário: <?php echo ($tipo_usuario === 'aluno' ? 'Aluno(a)' : 'Tutor(a)'); ?></p>
            <button onclick="window.location.href='perfil.php';">Ver Perfil Completo</button>
        </section>
        <!-- Fim Resumo do Perfil -->

    </main>
    <!-- Fim Conteúdo Principal -->

    <!-- Rodapé -->
    <footer class="footer">
        <p>UNIVESP PI 2024</p>
        <p><a href="https://github.com/UNIVESP-PI-BAURU/PI24b.git" target="_blank">https://github.com/UNIVESP-PI-BAURU/PI24b.git</a></p>
    </footer>

</body>
</html>
