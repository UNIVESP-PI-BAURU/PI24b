<?php
// Inicia a sessão e verifica login
session_start();
if (!isset($_SESSION['id_tutor'])) {
    header("Location: ../login.php");
    exit();
}

// Inclui o processamento da página
require_once 'proc_perfil.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil do Tutor</title>
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
    <a href="../logout.php">Logout</a>
</nav>

<!-- Conteúdo Principal -->
<main class="main-content">
    <div class="signup-section">
        <h2>Perfil do Tutor: <?php echo htmlspecialchars($usuario['nome']); ?></h2>

        <div class="foto-perfil">
            <div class="foto-moldura-perfil">
                <?php if (!empty($usuario['foto_perfil'])): ?>
                    <img src="<?php echo htmlspecialchars($usuario['foto_perfil']); ?>" alt="Avatar" class="avatar-perfil">
                <?php else: ?>
                    <p>Sem foto</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="info-usuario">
            <p><strong>Email:</strong> <?php echo htmlspecialchars($usuario['email']); ?></p>
            <p><strong>Cidade/Estado:</strong> <?php echo htmlspecialchars($usuario['cidade']) . ', ' . htmlspecialchars($usuario['estado']); ?></p>
            <p><strong>Data de Nascimento:</strong> <?php echo !empty($usuario['data_nascimento']) ? htmlspecialchars($usuario['data_nascimento']) : 'Não informado'; ?></p>
            <p><strong>Idiomas:</strong> <?php echo implode(', ', array_map('htmlspecialchars', $idiomas)); ?></p>
            <p><strong>Biografia:</strong> <?php echo htmlspecialchars($usuario['biografia']); ?></p>
        </div>

        <div class="actions">
            <button onclick="window.location.href='editar_perfil.php'">Editar Perfil</button>
            <button onclick="if(confirm('Você tem certeza que deseja excluir sua conta?')) { window.location.href='excluir_conta.php'; }">Excluir Conta</button>
        </div>

        <button onclick="window.location.href='./dashboard_tutor.php'">Voltar para Dashboard</button>
    </div>
</main>

<!-- Rodapé -->
<footer class="footer">
    <p>UNIVESP PI 2024</p>
</footer>

</body>
</html>
