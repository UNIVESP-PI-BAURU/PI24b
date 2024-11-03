<?php
require_once '../session_control.php'; // Inclui o controle de sessão

require_once '../conexao.php'; // Inclui a conexão com o banco

$id_usuario = $_SESSION['id_aluno']; // Mudança para id_aluno
$tabela_usuario = 'Alunos'; // Mudança para Alunos

// Consulta os dados do aluno
$sql = "SELECT nome, foto_perfil, cidade, estado FROM $tabela_usuario WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $id_usuario);
$stmt->execute();
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

// Finaliza a execução se o aluno não for encontrado
if (!$usuario) {
    header("Location: ../login.php");
    exit();
}

// O array $usuario agora está disponível para uso na dashboard de aluno
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Aluno</title> <!-- Mudança para Aluno -->
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
        <?php if (isset($_SESSION['id_aluno'])): ?> <!-- Mudança para id_aluno -->
            <a href="../logout.php">Logout</a>
        <?php else: ?>
            <a href="../login.php">Login</a>
        <?php endif; ?>
    </nav>
    <!-- Fim da Navegação -->

    <!-- Conteúdo Principal -->
    <main class="main-content">
        <section class="dashboard-section">

            <!-- Saudação -->
            <div class="signup-section">
                <h3>Bem-vindo (a), aluno(a) <?php echo htmlspecialchars($usuario['nome']); ?>!</h3> <!-- Mudança para aluno -->
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
                    <p>Aluno(a): <?php echo htmlspecialchars($usuario['nome']); ?></p> <!-- Mudança para aluno -->
                    <p>ID-A: # <?php echo htmlspecialchars($_SESSION['id_aluno']); ?></p> <!-- Mudança para id_aluno -->
                    <?php if (!empty($usuario['cidade']) || !empty($usuario['estado'])): ?>
                        <p>
                            <?php echo htmlspecialchars($usuario['cidade']) ? htmlspecialchars($usuario['cidade']) . ', ' : ''; ?>
                            <?php echo htmlspecialchars($usuario['estado']) ? htmlspecialchars($usuario['estado']) : ''; ?>
                        </p>
                    <?php endif; ?>
                    <button onclick="window.location.href='./perfil.php'">Ver meu perfil</button>
                </div>
            </div>

            <!-- Pesquisa -->
            <div class="signup-section" style="margin-top: 20px;">
                <h3>Encontre seus tutores aqui!</h3> <!-- Mudança para tutores -->
                <button onclick="window.location.href='./pesquisa_tutores.php'">Pesquisar Tutores</button> <!-- Mudança para tutores -->
            </div>

            <!-- Aulas -->
            <div class="signup-section" style="margin-top: 30px;">
                <h3>Aulas atribuídas:</h3>
                <!-- Conteúdo das aulas será inserido aqui -->
            </div>

        </section>
    </main>

    <!-- Rodapé -->
    <footer class="footer">
        <p>UNIVESP PI 2024</p>
    </footer>

</body>
</html>
