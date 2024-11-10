<?php
session_start();
require_once 'conexao.php'; 

// Verifica se o usuário está logado
if (!isset($_SESSION['id_usuario']) || !isset($_SESSION['tipo_usuario'])) {
    header("Location: login.php");
    exit();
}

$tipo_usuario = $_SESSION['tipo_usuario']; // Aluno ou Tutor
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesquisa</title>
    <link rel="stylesheet" href="ASSETS/CSS/style.css">
</head>
<body>

    <header class="header">
        <img src="ASSETS/IMG/capa.png" alt="Imagem de Capa">
    </header>

    <nav class="navbar">
        <button onclick="window.location.href='index.php'">Home</button>
        <button onclick="window.location.href='dashboard.php'">Dashboard</button>
        <button onclick="window.location.href='logout.php'">Logout</button>
    </nav>

    <!-- Conteúdo Principal -->
    <main class="main-content">
        <section class="signup-section">
            <h3>Pesquisar <?php echo ($tipo_usuario === 'aluno') ? 'Tutores' : 'Alunos'; ?></h3>
            
            <form method="GET" action="resultados_pesquisa.php">
                <label for="idioma">Idioma:</label>
                <input type="text" id="idioma" name="idioma" placeholder="Ex: Inglês, Espanhol">
                <br>
                <label for="cidade">Cidade:</label>
                <input type="text" id="cidade" name="cidade" placeholder="Ex: São Paulo">
                <br>
                <label for="estado">Estado:</label>
                <input type="text" id="estado" name="estado" placeholder="Ex: SP">
                <br>
                <button type="submit">Pesquisar</button>
            </form>

            <!-- Botão para retornar à Dashboard -->
            <button onclick="window.location.href='dashboard.php'">Retornar à Dashboard</button>

        </section>
    </main>

    <!-- Rodapé -->
    <footer class="footer">
        <p>UNIVESP PI 2024</p>
        <p><a href="https://github.com/UNIVESP-PI-BAURU/PI24b.git" target="_blank">https://github.com/UNIVESP-PI-BAURU/PI24b.git</a></p>
    </footer>
    
</body>
</html>
