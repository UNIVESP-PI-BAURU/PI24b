<?php
// Inicia a sessão e verifica login
session_start();
if (!isset($_SESSION['id']) || !isset($_SESSION['tipo'])) {
    header("Location: login.php");
    exit();
}

// Inclui a conexão com o banco
require_once 'conexao.php';

// Determina o tipo de usuário e busca os dados
$tipo_usuario = $_SESSION['tipo']; // 'aluno' ou 'tutor'
$id_usuario = $_SESSION['id']; // ID comum para todos os tipos
$tabela_usuario = ($tipo_usuario === 'aluno') ? 'Alunos' : 'Tutores';

// Consulta SQL para buscar dados do usuário
$sql = "SELECT nome, email, foto_perfil, cidade, estado, data_nascimento, biografia, idioma
        FROM $tabela_usuario WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $id_usuario, PDO::PARAM_INT);
$stmt->execute();

$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

// Verifica se encontrou o usuário
if (!$usuario) {
    die("Usuário não encontrado.");
}

// Exibe o idioma
$idioma = !empty($usuario['idioma']) ? $usuario['idioma'] : '';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil</title>
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

<!-- Conteúdo Principal -->
<main class="main-content">
    <div class="signup-section">
        <h2>Editar Perfil de <?php echo ($tipo_usuario === 'tutor' ? "Tutor(a)" : "Aluno(a)"); ?>: <?php echo htmlspecialchars($usuario['nome']); ?></h2>

        <form action="proc_editar_perfil.php" method="POST" enctype="multipart/form-data">
            <div class="input-field">
                <label for="nome">Nome</label>
                <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($usuario['nome']); ?>" required>
            </div>

            <div class="input-field">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" required>
            </div>

            <div class="input-field">
                <label for="cidade">Cidade</label>
                <input type="text" id="cidade" name="cidade" value="<?php echo htmlspecialchars($usuario['cidade']); ?>">
            </div>

            <div class="input-field">
                <label for="estado">Estado</label>
                <input type="text" id="estado" name="estado" value="<?php echo htmlspecialchars($usuario['estado']); ?>">
            </div>

            <div class="input-field">
                <label for="data_nascimento">Data de Nascimento</label>
                <input type="date" id="data_nascimento" name="data_nascimento" value="<?php echo htmlspecialchars($usuario['data_nascimento']); ?>">
            </div>

            <div class="input-field">
                <label for="biografia">Biografia</label>
                <textarea id="biografia" name="biografia"><?php echo htmlspecialchars($usuario['biografia']); ?></textarea>
            </div>

            <div class="input-field">
                <label for="idioma">Idioma</label>
                <input type="text" id="idioma" name="idioma" value="<?php echo htmlspecialchars($idioma); ?>">
            </div>

            <div class="input-field">
                <label for="foto_perfil">Foto de Perfil</label>
                <input type="file" id="foto_perfil" name="foto_perfil">
                <p>Foto atual: <?php echo !empty($usuario['foto_perfil']) ? $usuario['foto_perfil'] : 'Nenhuma foto'; ?></p>
            </div>

            <button type="submit">Salvar Alterações</button>
        </form>
    </div>
</main>

<!-- Rodapé -->
<footer class="footer">
    <p>UNIVESP PI 2024</p>
</footer>

</body>
</html>
