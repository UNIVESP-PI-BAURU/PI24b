<?php
// Inicia a sessão e verifica login
session_start();
if (!isset($_SESSION['id_aluno']) && !isset($_SESSION['id_tutor'])) {
    header("Location: ../login.php");
    exit();
}

// Inclui o processamento dos dados para preencher o formulário
require_once 'proc_editar_perfil.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil</title>
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
    <?php if (isset($_SESSION['id_aluno']) || isset($_SESSION['id_tutor'])): ?>
        <a href="../logout.php">Logout</a>
    <?php else: ?>
        <a href="../login.php">Login</a>
    <?php endif; ?>
</nav>

<!-- Conteúdo Principal -->
<main class="main-content">
    <section class="perfil-section">
        <h1>Editar Perfil de <?php echo ($tipo_usuario === 'tutor' ? "Tutor(a)" : "Aluno(a)"); ?></h1>

        <form class="perfil-form" action="proc_editar_perfil.php" method="post">
            <input type="hidden" name="id_usuario" value="<?php echo htmlspecialchars($id_usuario); ?>">

            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($usuario['nome']); ?>" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" required>

            <label for="cidade">Cidade:</label>
            <input type="text" id="cidade" name="cidade" value="<?php echo htmlspecialchars($usuario['cidade']); ?>">

            <label for="estado">Estado:</label>
            <input type="text" id="estado" name="estado" value="<?php echo htmlspecialchars($usuario['estado']); ?>">

            <label for="data_nascimento">Data de Nascimento:</label>
            <input type="date" id="data_nascimento" name="data_nascimento" value="<?php echo htmlspecialchars($usuario['data_nascimento']); ?>">

            <label for="biografia">Biografia:</label>
            <textarea id="biografia" name="biografia"><?php echo htmlspecialchars($usuario['biografia']); ?></textarea>

            <label for="idioma">Idiomas:</label>
            <input type="text" id="idioma" name="idioma[]" placeholder="Adicione idiomas separados por vírgulas">

            <button type="submit">Salvar</button>
        </form>

        <button onclick="window.location.href='./perfil.php'">Cancelar</button>
    </section>
</main>

<!-- Rodapé -->
<footer class="footer">
    <p>UNIVESP PI 2024</p>
</footer>

</body>
</html>
