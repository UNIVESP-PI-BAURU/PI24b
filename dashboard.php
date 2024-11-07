<?php
session_start(); // Inicia a sessão

// Verifica se o usuário está logado e redireciona para login se não estiver
if (!isset($_SESSION['id']) || !isset($_SESSION['tipo'])) {
    header("Location: login.php"); // Redireciona para login
    exit(); // Evita que o código continue
}

require_once 'conexao.php'; // Inclui a conexão com o banco

// Verifica a conexão com o banco de dados
if (!$conn) {
    die("Falha na conexão com o banco de dados.");
}

// Define o tipo de usuário e busca os dados
$tipo_usuario = $_SESSION['tipo']; // Pode ser 'aluno' ou 'tutor'
$id_usuario = $_SESSION['id']; // ID comum para todos os tipos
$tabela_usuario = ($tipo_usuario === 'aluno') ? 'Alunos' : 'Tutores';

// Consulta os dados do usuário
$sql = "SELECT nome, foto_perfil, cidade, estado FROM $tabela_usuario WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $id_usuario);
$stmt->execute();
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

// Se o usuário não for encontrado, redireciona para o login
if (!$usuario) {
    header("Location: login.php");
    exit();
}
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
        <a href="index.php">Home</a>
        <a href="sobre_nos.php">Sobre nós</a>
        <a href="dashboard.php">Dashboard</a> <!-- Alterado para uma única dashboard -->
        <a href="logout.php">Logout</a>
    </nav>

    <!-- Conteúdo Principal -->
    <main class="main-content">
        <section class="dashboard-section">

        <!-- Saudação -->
        <div class="signup-section">
            <h3>Bem-vindo(a) <?php echo ($tipo_usuario === 'aluno' ? 'Aluno(a), ' : 'Tutor(a), '); ?><?php echo htmlspecialchars($usuario['nome']); ?>!</h3>
        </div>
        <!-- fim Saudação -->

        <!-- Perfil -->
        <div class="signup-section" style="display: flex; align-items: center; margin-bottom: 20px;">
            <div style="flex: 1;">
                <div class="foto-moldura-dashboard">
                    <?php if (!empty($usuario['foto_perfil'])): ?>
                        <img src="<?php echo htmlspecialchars($usuario['foto_perfil']); ?>" alt="Avatar" class="avatar-dashboard">
                    <?php else: ?>
                        <p>Não há foto</p>
                    <?php endif; ?>
                </div>
            </div>
            <div style="flex: 2; padding-left: 10px;">
                <p><?php echo ($tipo_usuario === "tutor" ? "Tutor(a): " : "Aluno(a): ") . htmlspecialchars($usuario['nome']); ?></p>
                
                <!-- Exibe cidade e estado, caso existam -->
                <?php if (!empty($usuario['cidade']) || !empty($usuario['estado'])): ?>
                    <p>
                        <?php 
                            echo htmlspecialchars($usuario['cidade']) ? htmlspecialchars($usuario['cidade']) . ', ' : ''; 
                            echo htmlspecialchars($usuario['estado']) ? htmlspecialchars($usuario['estado']) : ''; 
                        ?>
                    </p>
                <?php endif; ?>
                
                <button onclick="window.location.href='./perfil.php'">Ver meu perfil</button>
            </div>
        </div>
        <!-- fim Perfil -->

        <!-- Pesquisa -->
        <div class="signup-section" style="margin-top: 20px;">
            <?php if ($tipo_usuario === 'aluno'): ?>
                <a href="pesquisa.php" class="custom-button">Pesquisar Tutores</a>
            <?php elseif ($tipo_usuario === 'tutor'): ?>
                <a href="pesquisa.php" class="custom-button">Pesquisar Alunos</a>
            <?php endif; ?>
        </div>
        <!-- Fim Pesquisa -->

        </section>
    </main>
    <!-- fim Conteúdo Principal -->

    <!-- Rodapé -->
    <footer class="footer">
        UNIVESP PI 2024
    </footer>

</body>
</html>
