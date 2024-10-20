<?php
// Inicia a sessão
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['id_aluno']) && !isset($_SESSION['id_tutor'])) {
    header("Location: ../login.php");
    exit();
}

// Incluir conexão com o banco de dados
require_once '../conexao.php';
if (!$conn) {
    die("Falha na conexão com o banco de dados.");
}

// Determina o tipo de usuário e busca os dados
$tipo_usuario = isset($_SESSION['id_aluno']) ? 'aluno' : 'tutor';
$id_usuario = $_SESSION['id_' . $tipo_usuario];
$tabela_usuario = ($tipo_usuario === 'aluno') ? 'Alunos' : 'Tutores';

// Consulta SQL para buscar todos os dados do usuário
$sql = "SELECT nome, email, foto_perfil, cidade, estado, data_nascimento, biografia, idiomas 
        FROM $tabela_usuario 
        WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $id_usuario);
$stmt->execute();
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

// Verifica se os dados foram encontrados
if (!$usuario) {
    echo "<p>Usuário não encontrado.</p>";
    exit();
}

// Verifica se o campo idiomas existe e não é nulo
$idiomas = isset($usuario['idiomas']) ? explode(',', $usuario['idiomas']) : [];

// Começa a gerar a página HTML
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
    <?php if (isset($_SESSION['id_aluno']) || isset($_SESSION['id_tutor'])): ?>
        <a href="../logout.php">Logout</a>
    <?php else: ?>
        <a href="../login.php">Login</a>
    <?php endif; ?>
</nav>
<!-- fim Navegação -->

<!-- Conteúdo Principal -->
<main class="main-content">
    <section class="perfil-section">
        <h1>Perfil de <?php echo ($tipo_usuario === 'tutor' ? "Tutor(a)" : "Aluno(a)"); ?>: <?php echo htmlspecialchars($usuario['nome']); ?></h1>

        <div class="foto-perfil">
            <?php if (!empty($usuario['foto_perfil'])): ?>
                <img src="<?php echo htmlspecialchars($usuario['foto_perfil']); ?>" alt="Avatar" style="width: 150px; height: 150px; border-radius: 50%;">
            <?php else: ?>
                <p>Sem foto</p>
            <?php endif; ?>
        </div>

        <div class="info-usuario">
            <p><strong>Email:</strong> <?php echo htmlspecialchars($usuario['email']); ?></p>
            <p><strong>Cidade/Estado:</strong> 
                <?php echo htmlspecialchars($usuario['cidade']); ?>, <?php echo htmlspecialchars($usuario['estado']); ?>
            </p>
            <p><strong>Data de Nascimento:</strong> 
                <?php echo !empty($usuario['data_nascimento']) ? htmlspecialchars($usuario['data_nascimento']) : 'Não informado'; ?>
            </p>
            <p><strong>Idiomas:</strong> 
                <?php echo implode(', ', array_map('htmlspecialchars', $idiomas)); ?>
            </p>
            <p><strong>Biografia:</strong> <?php echo htmlspecialchars($usuario['biografia']); ?></p>
        </div>

        <div class="actions">
            <button onclick="window.location.href='editar_perfil.php'">Editar Perfil</button>
            <button onclick="if(confirm('Você tem certeza que deseja excluir sua conta?')) { window.location.href='excluir_conta.php'; }">Excluir Conta</button>
        </div>

        <!-- Botão para voltar -->
        <button onclick="window.location.href='./dashboard_aluno.php'">Voltar para Dashboard</button>
    </section>
</main>

<!-- Rodapé -->
<footer class="footer">
    <p>UNIVESP PI 2024</p>
</footer>

</body>
</html>
