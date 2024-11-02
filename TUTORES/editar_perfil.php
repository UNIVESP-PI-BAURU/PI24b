<?php
// Inicia a sessão e verifica login
session_start();
if (!isset($_SESSION['id_tutor'])) {
    error_log("Tutor não logado, redirecionando para login.");
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
    <title>Editar Perfil - Tutor</title>
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
<div class="main-content">
    <div class="signup-section">
        <h2>Editar Perfil de Tutor</h2>

        <!-- Formulário de Edição de Perfil -->
        <form class="signup-form" action="proc_editar_perfil.php" method="post" enctype="multipart/form-data">

            <!-- Campo para nome -->
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($nome); ?>" required>
            <br>
            
            <!-- Campo para email -->
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
            <br>

            <!-- Campo para cidade -->
            <label for="cidade">Cidade:</label>
            <input type="text" id="cidade" name="cidade" value="<?php echo htmlspecialchars($cidade); ?>">
            <br>

            <!-- Campo para estado -->
            <label for="estado">Estado:</label>
            <input type="text" id="estado" name="estado" value="<?php echo htmlspecialchars($estado); ?>">
            <br>

            <!-- Campo para data de nascimento -->
            <label for="data_nascimento">Data de Nascimento:</label>
            <input type="date" id="data_nascimento" name="data_nascimento" value="<?php echo htmlspecialchars($data_nascimento); ?>">
            <br>

            <!-- Campo para biografia -->
            <label for="biografia">Biografia:</label>
            <textarea id="biografia" name="biografia"><?php echo htmlspecialchars($biografia); ?></textarea>
            <br>

            <!-- Campo para foto de perfil -->
            <label for="foto_perfil">Foto de Perfil:</label>
            <input type="file" id="foto_perfil" name="foto_perfil" accept="image/*">
            <br>

            <!-- Idiomas -->
            <div id="idiomas">
                <label for="idioma">Idioma:</label>
                <input type="text" id="idioma" name="idiomas[]" value="<?php echo htmlspecialchars($idiomas[0]); ?>" required>
                <button type="button" onclick="addCampoIdioma()">Adicionar mais um</button>
            </div>
            <br>

            <!-- Botão submeter -->
            <button type="submit" name="atualizar">Atualizar</button>
        </form>
        <br>

        <!-- Botão para retornar ao perfil -->
        <button type="button" onclick="retornarPerfil()">Retornar ao Perfil</button>

    </div>
</div>

<!-- Rodapé -->
<div class="footer">
    UNIVESP PI 2024
</div>

<!-- Scripts -->
<script>
    function addCampoIdioma() {
        var divIdiomas = document.getElementById('idiomas');
        var input = document.createElement('input');
        input.type = 'text';
        input.name = 'idiomas[]';
        divIdiomas.appendChild(input);
    }
    
    function retornarPerfil() {
        window.location.href = 'perfil.php';
    }
</script>

</body>
</html>
