<?php

// Verifica se o usuário está logado
if (!isset($_SESSION['id']) || !isset($_SESSION['tipo'])) {
    header("Location: login.php");
    exit();
}

// Inclui o processamento do perfil
require_once 'proc_perfil.php';

// Debug: Verifica se as variáveis da sessão estão corretamente definidas
echo "ID do usuário: " . (isset($_SESSION['id']) ? $_SESSION['id'] : 'Não definido') . "<br>";  // Debug
echo "Tipo do usuário: " . (isset($_SESSION['tipo']) ? $_SESSION['tipo'] : 'Não definido') . "<br>";  // Debug

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
    <?php if (isset($_SESSION['id'])): ?>
        <a href="../logout.php">Logout</a>
    <?php else: ?>
        <a href="../login.php">Login</a>
    <?php endif; ?>
</nav>

<!-- Conteúdo Principal -->
<main class="main-content">
    <div class="signup-section">
        <h2>Perfil de <?php echo ($tipo_usuario === 'tutor' ? "Tutor(a)" : "Aluno(a)"); ?>: <?php echo htmlspecialchars($usuario['nome']); ?></h2>

        <div class="foto-perfil">
            <div class="foto-moldura-perfil">
                <?php if (!empty($usuario['foto_perfil'])): ?>
                    <img src="../uploads/fotos_perfil/<?php echo htmlspecialchars($usuario['foto_perfil']); ?>" alt="Avatar" class="avatar-perfil">
                <?php else: ?>
                    <p>Sem foto</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="info-usuario">
            <p><strong>Email:</strong> <?php echo htmlspecialchars($usuario['email']); ?></p>
            <p><strong>Cidade/Estado:</strong> <?php echo htmlspecialchars($usuario['cidade']) . ', ' . htmlspecialchars($usuario['estado']); ?></p>
            <p><strong>Data de Nascimento:</strong> <?php echo !empty($usuario['data_nascimento']) ? htmlspecialchars($usuario['data_nascimento']) : 'Não informado'; ?></p>
            <p><strong>Idioma:</strong> <?php echo htmlspecialchars($usuario['idioma']) ? htmlspecialchars($usuario['idioma']) : 'Não informado'; ?></p>
            <p><strong>Biografia:</strong> <?php echo htmlspecialchars($usuario['biografia']); ?></p>
        </div>

        <div class="actions">
            <button onclick="window.location.href='editar_perfil.php'">Editar Perfil</button>
            <button onclick="if(confirm('Você tem certeza que deseja excluir sua conta?')) { window.location.href='excluir_conta.php'; }">Excluir Conta</button>
        </div>

        <button onclick="window.location.href='./dashboard.php'">Voltar para Dashboard</button>
    </div>
</main>

<!-- Rodapé -->
<footer class="footer">
    <p>UNIVESP PI 2024</p>
</footer>

</body>
</html>
