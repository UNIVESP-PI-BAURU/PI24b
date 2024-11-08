<?php
session_start(); // Inicia a sessão

// Saída de depuração para verificar as variáveis de sessão
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

// Verifica se o usuário está logado e redireciona para login se não estiver
if (!isset($_SESSION['id_usuario']) || !isset($_SESSION['tipo_usuario'])) {
    header("Location: login.php"); // Redireciona para login
    exit(); // Evita que o código continue
}

// Define o tipo de usuário e carrega o nome do usuário da sessão
$tipo_usuario = $_SESSION['tipo_usuario']; // 'aluno' ou 'tutor'
$nome_usuario = $_SESSION['nome_usuario'] ?? 'Visitante'; // Nome do usuário ou "Visitante" se não estiver definido

// Saída de depuração para verificar nome do usuário
echo "Nome do usuário: " . htmlspecialchars($nome_usuario);
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

        <!-- Saudação -->
        <section class="signup-section">
            <div class="signup-section">
                <h3>Bem-vindo(a) <?php echo ($tipo_usuario === 'aluno' ? 'Aluno(a), ' : 'Tutor(a), '); ?><?php echo htmlspecialchars($nome_usuario); ?>!</h3>
            </div>
        </section>
        <!-- Fim Saudação -->
        
    </main>
    <!-- fim Conteúdo Principal -->

    <!-- Rodapé -->
    <footer class="footer">
        <p> 2024 - UNIVESP</p>
    </footer>

</body>
</html>
