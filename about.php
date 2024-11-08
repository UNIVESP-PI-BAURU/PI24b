<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['id_usuario']) || !isset($_SESSION['tipo_usuario'])) {
    header("Location: login.php");
    exit();
}

require_once 'conexao.php'; // Inclui a conexão com o banco de dados

// Verifica a conexão com o banco de dados
if (!$conn) {
    die("Falha na conexão com o banco de dados.");
}

// Obtém o ID do perfil visitado da URL
$id_perfil = isset($_GET['id']) ? $_GET['id'] : null;
if (!$id_perfil) {
    header("Location: dashboard.php"); // Redireciona se o ID não for válido
    exit();
}

// Verifica o tipo do usuário logado
$tipo_usuario_logado = $_SESSION['tipo_usuario'];

// Define o tipo de usuário do perfil visitado (oposto ao usuário logado)
$tabela_perfil = ($tipo_usuario_logado === 'aluno') ? 'Tutores' : 'Alunos';

// Consulta os dados do usuário selecionado no perfil
$sql = "SELECT id, nome, foto_perfil, cidade, estado, idiomas, biografia FROM $tabela_perfil WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $id_perfil, PDO::PARAM_INT);
$stmt->execute();
$perfil_usuario = $stmt->fetch(PDO::FETCH_ASSOC);

// Verifica se o perfil foi encontrado
if (!$perfil_usuario) {
    header("Location: dashboard.php"); // Redireciona se o perfil não existir
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de <?php echo htmlspecialchars($perfil_usuario['nome']); ?></title>
    <link rel="stylesheet" href="ASSETS/CSS/style.css">
</head>
<body>

    <header class="header">
        <img src="ASSETS/IMG/capa.png" alt="Capa do site">
    </header>

    <nav class="navbar">
        <button onclick="window.location.href='index.php'">Home</button>
        <button onclick="window.location.href='dashboard.php'">Dashboard</button>
        <button onclick="window.location.href='logout.php'">Logout</button>
    </nav>

    <main class="main-content">
        <section class="profile-section">
            <h2><?php echo htmlspecialchars($perfil_usuario['nome']); ?></h2>
            <div style="display: flex; align-items: center; margin-bottom: 20px;">
                <div class="foto-moldura">
                    <?php if (!empty($perfil_usuario['foto_perfil'])): ?>
                        <img src="<?php echo htmlspecialchars($perfil_usuario['foto_perfil']); ?>" alt="Foto de Perfil" class="avatar">
                    <?php else: ?>
                        <p>Sem foto de perfil</p>
                    <?php endif; ?>
                </div>
                <div style="padding-left: 20px;">
                    <p><strong>Cidade/Estado:</strong> <?php echo htmlspecialchars($perfil_usuario['cidade'] . ', ' . $perfil_usuario['estado']); ?></p>
                    <p><strong>Idiomas:</strong> <?php echo htmlspecialchars($perfil_usuario['idiomas']); ?></p>
                    <p><strong>Biografia:</strong> <?php echo nl2br(htmlspecialchars($perfil_usuario['biografia'])); ?></p>
                </div>
            </div>

            <div class="interaction-buttons">
                <!-- Botão para iniciar chat -->
                <button onclick="window.location.href='chat.php?id=<?php echo $perfil_usuario['id']; ?>'">Iniciar Chat</button>
                
                <!-- Botão para contratar ou agendar aula -->
                <button onclick="window.location.href='agendar.php?id=<?php echo $perfil_usuario['id']; ?>'">Contratar/Agendar Aula</button>

                <?php if ($tipo_usuario_logado === 'aluno'): ?>
                    <!-- Botão de curtir, implementável posteriormente -->
                    <button>Curtir</button>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <footer class="footer">
        UNIVESP PI 2024
    </footer>
    
</body>
</html>
