<?php
// Inicia a sessão
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['id_aluno']) && !isset($_SESSION['id_tutor'])) {
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>
    <link rel="stylesheet" href="../ASSETS/CSS/style.css">
</head>
<body>

<!-- Cabeçalho -->
<header class="header">
    <img src="../ASSETS/IMG/capa.png" alt="Capa do Site">
</header>

<!-- Navegação -->
<nav class="navbar">
    <a href="../index.php">Home</a>
    <a href="../sobre_nos.php">Sobre nós</a>
    <a href="../login.php">Logoff</a>
    <a href="./dashboard_aluno.php">Dashboard</a>
</nav>

<!-- Conteúdo Principal -->
<main class="main-content">
    <section class="perfil-section">
        <?php
        // Inclui o processamento do perfil
        require_once 'proc_perfil.php';
        ?>

        <!-- Botão para voltar -->
        <button onclick="window.location.href='./dashboard_aluno.php'">Voltar para Dashboard</button>
    </section>
</main>

<!-- Rodapé -->
<footer class="footer">
    <p>UNIVESP PI 2024</p>
</footer>

</body>
</html>
