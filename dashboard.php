<?php
// Conexão com o banco de dados
require_once 'conexao.php';

// Inicialização da sessão
session_start();

// Verifica se o usuário está logado e redireciona para login se não estiver
if (!isset($_SESSION['id_usuario']) || !isset($_SESSION['tipo_usuario'])) {
    echo "Usuário não logado! Redirecionando para login..."; // Debug
    header("Location: login.php"); // Redireciona para login
    exit(); // Evita que o código continue
}

// Define o tipo de usuário e carrega o nome do usuário da sessão
$tipo_usuario = $_SESSION['tipo_usuario']; // 'aluno' ou 'tutor'
$nome_usuario = $_SESSION['nome_usuario'] ?? 'Visitante'; // Nome do usuário ou "Visitante" se não estiver definido

// Debug: Exibe o tipo e nome do usuário
echo "Tipo de usuário: $tipo_usuario<br>";
echo "Nome de usuário: $nome_usuario<br>";
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
            <?php
                // Debug: Exibe o tipo de usuário no HTML
                echo "<br>Debug Saudação: Nome: $nome_usuario - Tipo: $tipo_usuario";
            ?>
        </section>
        <!-- Fim Saudação -->

        <!-- complemento: Resumo Perfil -->
        <section class="perfil-resumo">
            <h4>Resumo do Perfil</h4>
            <?php
                // Aqui você pode adicionar mais informações do perfil (exemplo: foto, cidade, idioma)
                // Exemplo de debug para verificar se as variáveis estão carregando corretamente
                echo "Debug Resumo Perfil: Nome: $nome_usuario<br>";
                echo "Tipo de usuário: $tipo_usuario<br>";

                // Se a foto do usuário estiver armazenada na sessão, exibe-a
                if (isset($_SESSION['foto_usuario']) && !empty($_SESSION['foto_usuario'])) {
                    $foto_usuario = $_SESSION['foto_usuario'];
                    echo "<img src='ASSETS/IMG/$foto_usuario' alt='Foto do usuário' class='avatar-dashboard'><br>";
                    echo "Foto do usuário: $foto_usuario<br>";
                } else {
                    echo "Foto não encontrada para o usuário.<br>";
                }
            ?>
            <section class="perfil-completo">
                <button onclick="window.location.href='perfil.php';">Ver Perfil Completo</button>
                <?php
                    // Debug: Exibe a URL para onde o botão vai redirecionar
                    echo "Debug: Botão redireciona para perfil.php<br>";
                ?>
            </section>
        </section>
        <!-- Fim Resumo Perfil -->

    </main>
    <!-- Fim Conteúdo Principal -->

    <!-- Rodapé -->
    <footer class="footer">
        <p>UNIVESP PI 2024</p>
        <p><a href="https://github.com/UNIVESP-PI-BAURU/PI24b.git" target="_blank">https://github.com/UNIVESP-PI-BAURU/PI24b.git</a></p>
    </footer>

</body>
</html>
