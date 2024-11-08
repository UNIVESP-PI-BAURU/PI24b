<?php
session_start(); // Inicia a sessão

// Verifica se o usuário está logado e redireciona para login se não estiver
if (!isset($_SESSION['id_usuario']) || !isset($_SESSION['tipo_usuario'])) {
    header("Location: login.php"); // Redireciona para login
    exit(); // Evita que o código continue
}

require_once 'conexao.php'; // Inclui a conexão com o banco

// Verifica a conexão com o banco de dados
if (!$conn) {
    die("Falha na conexão com o banco de dados.");
}

// Define o tipo de usuário e busca os dados
$tipo_usuario = $_SESSION['tipo_usuario']; // 'aluno' ou 'tutor'
$id_usuario = $_SESSION['id_usuario']; // ID do usuário, seja aluno ou tutor

// Exibindo dados de depuração
echo "<!-- Debugging - Tipo de Usuário e ID do Usuário -->";
var_dump($tipo_usuario, $id_usuario); // Verificando as variáveis de sessão

// Define a tabela de usuários com base no tipo de usuário (aluno ou tutor)
$tabela_usuario = ($tipo_usuario === 'aluno') ? 'Alunos' : 'Tutores';

// Consulta os dados do usuário
$sql = "SELECT nome, foto_perfil, cidade, estado FROM $tabela_usuario WHERE id = :id"; // Alterado para 'id' comum
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $id_usuario);
$stmt->execute();
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

// Exibindo dados do usuário para depuração
echo "<!-- Debugging - Dados do Usuário Encontrado -->";
var_dump($usuario);  // Verificando os dados retornados

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
        <button onclick="window.location.href='index.php';">Home</button>
        <button onclick="window.location.href='sobre_nos.php';">Sobre nós</button>
        <button onclick="window.location.href='dashboard.php';">Dashboard</button>
        <button onclick="window.location.href='logout.php';">Logout</button>
    </nav>
    <!-- Fim Navegação -->

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
                
                <button onclick="window.location.href='./perfil.php';">Ver meu perfil</button>
            </div>
        </div>
        <!-- fim Perfil -->

        <!-- Pesquisa -->
        <div class="signup-section" style="margin-top: 20px;">
            <!-- Condicional para mostrar o texto correto -->
            <?php if ($tipo_usuario === 'aluno'): ?>
                <button onclick="window.location.href='pesquisa.php';" class="custom-button">Pesquisar Tutores</button>
            <?php elseif ($tipo_usuario === 'tutor'): ?>
                <button onclick="window.location.href='pesquisa.php';" class="custom-button">Pesquisar Alunos</button>
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
