<?php
session_start();

// Verifica se o usuário está logado e redireciona para login se não estiver
if (!isset($_SESSION['id_usuario']) || !isset($_SESSION['tipo_usuario'])) {
    header("Location: login.php");
    exit();
}

require_once 'conexao.php'; // Inclui a conexão com o banco

// Define o tipo de usuário e busca os dados
$tipo_usuario = $_SESSION['tipo_usuario']; // Pode ser 'aluno' ou 'tutor'
$id_usuario = $_SESSION['id_usuario']; // ID comum para todos os tipos
$tabela_usuario = ($tipo_usuario === 'aluno') ? 'Alunos' : 'Tutores';

// Consulta os dados do usuário
$sql = "SELECT nome, foto_perfil, cidade, estado FROM $tabela_usuario WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $id_usuario);
$stmt->execute();
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

// Finaliza a execução se o usuário não for encontrado
if (!$usuario) {
    header("Location: login.php");
    exit();
}

// O array $usuario agora está disponível para uso na dashboard
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Conectando Interesses</title>
    <link rel="stylesheet" href="ASSETS/CSS/style.css">
</head>
<body>

    <!-- Cabeçalho -->
    <header class="header">
        <img src="ASSETS/IMG/capa.png" alt="Capa do Site">
    </header>

    <!-- Navegação -->
    <nav class="navbar">
        <a href="index.php">Home</a>
        <a href="sobre_nos.php">Sobre nós</a>
        <a href="logout.php">Logout</a>
    </nav>
    <!-- Fim da Navegação -->

    <!-- Conteúdo Principal -->
    <main class="main-content">
        <section class="dashboard-section">

            <!-- Saudação -->
            <div class="signup-section">
                <h3>Bem-vindo, <?php echo htmlspecialchars($usuario['nome']); ?>!</h3>
            </div>

            <!-- Perfil -->
            <div class="signup-section" style="display: flex; align-items: center; margin-bottom: 20px;">
                <div style="flex: 1;">
                    <div class="foto-moldura-dashboard">
                        <?php if (!empty($usuario['foto_perfil'])): ?>
                            <img src="<?php echo htmlspecialchars($usuario['foto_perfil']); ?>" alt="Avatar" class="avatar-dashboard">
                        <?php else: ?>
                            <p>Sem foto</p>
                        <?php endif; ?>
                    </div>
                </div>
                <div style="flex: 2; padding-left: 10px;">
                    <p><?php echo ($tipo_usuario === "tutor" ? "Tutor(a): " : "Aluno(a): ") . htmlspecialchars($usuario['nome']); ?></p>
                    <?php if (!empty($usuario['cidade']) || !empty($usuario['estado'])): ?>
                        <p>
                            <?php echo htmlspecialchars($usuario['cidade']) ? htmlspecialchars($usuario['cidade']) . ', ' : ''; ?>
                            <?php echo htmlspecialchars($usuario['estado']) ? htmlspecialchars($usuario['estado']) : ''; ?>
                        </p>
                    <?php endif; ?>
                    <button onclick="window.location.href='./perfil.php'">Ver meu perfil</button>
                </div>
            </div>

            <!-- Pesquisa (visível apenas para Alunos) -->
            <?php if ($tipo_usuario === 'aluno'): ?>
            <div class="signup-section" style="margin-top: 20px;">
                <h3>Encontre seu tutor aqui!</h3>
                <br>
                <input type="text" placeholder="Pesquise por tutores..." />
                <button>Pesquisar</button>
            </div>
            <?php endif; ?>

            <!-- Aulas (visível apenas para Alunos e Tutores) -->
            <div class="signup-section" style="margin-top: 30px;">
                <h3>Aulas em andamento:</h3>
                <!-- Aqui será inserido conteúdo dinâmico de aulas em andamento -->
                <div id="aulas-em-andamento">
                    <!-- Exemplo de exibição de uma aula -->
                    <p><strong>Matemática:</strong> Aula com João Silva</p>
                    <p><strong>Inglês:</strong> Aula com Maria Souza</p>
                </div>
            </div>

            <!-- Conteúdo específico para o Tutor -->
            <?php if ($tipo_usuario === 'tutor'): ?>
                <div class="signup-section" style="margin-top: 30px;">
                    <h3>Informações do Tutor</h3>
                    <p>Adicione detalhes específicos aqui (ex.: horários disponíveis, especialidades, etc.)</p>
                </div>
            <?php endif; ?>

        </section>
    </main>

    <!-- Rodapé -->
    <footer class="footer">
        <p>UNIVESP PI 2024</p>
    </footer>

</body>
</html>
