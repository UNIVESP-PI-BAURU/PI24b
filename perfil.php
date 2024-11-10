<?php
// Conexão com o banco de dados
require_once 'conexao.php';

// Inicialização da sessão
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['id_usuario']) || !isset($_SESSION['tipo_usuario'])) {
    header("Location: login.php?msg=precisa-logar");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];
$tipo_usuario = $_SESSION['tipo_usuario']; // 'aluno' ou 'tutor'

// Definir qual tabela usar com base no tipo de usuário
if ($tipo_usuario == 'aluno') {
    $tabela = 'Alunos';
} elseif ($tipo_usuario == 'tutor') {
    $tabela = 'Tutores';
} else {
    // Caso o tipo de usuário seja inválido, redireciona
    header("Location: login.php?msg=tipo-usuario-invalido");
    exit();
}

// Consulta os dados do usuário (ajustado para a tabela correta)
$query = "SELECT * FROM $tabela WHERE id = :id_usuario";
$stmt = $conn->prepare($query); // Usando $conn
$stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
$stmt->execute();
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

// Se o usuário não for encontrado, redireciona para o login
if (!$usuario) {
    header("Location: login.php?msg=usuario-nao-encontrado");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Perfil</title>
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
            <section class="signup-section">
                <h3>Perfil de <?php echo htmlspecialchars($usuario['nome']); ?></h3>

                <!-- Exibição da foto de perfil -->
                <div class="perfil-foto">
                    <?php if (!empty($usuario['foto_perfil'])): ?>
                        <img src="<?php echo htmlspecialchars($usuario['foto_perfil']); ?>" alt="Foto de Perfil" class="foto-perfil">
                    <?php else: ?>
                        <p>Foto não cadastrada.</p>
                    <?php endif; ?>
                </div>

                <!-- Exibição das informações do perfil -->
                <div class="informacoes-perfil">
                    <p><strong>Nome:</strong> <?php echo htmlspecialchars($usuario['nome']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($usuario['email']); ?></p>
                    <p><strong>Idiomas:</strong> <?php echo htmlspecialchars($usuario['idiomas']); ?></p>
                    <p><strong>Biografia:</strong> <?php echo htmlspecialchars($usuario['biografia']); ?></p>
                    <p><strong>Cidade:</strong> <?php echo htmlspecialchars($usuario['cidade']); ?></p>
                    <p><strong>Estado:</strong> <?php echo htmlspecialchars($usuario['estado']); ?></p>
                </div>

                <!-- Botão para editar perfil -->
                <button onclick="window.location.href='editar_perfil.php';">Editar Perfil</button>

            </section>
        </main>
        <!-- fim Conteúdo Principal -->

        <!-- Rodapé -->
        <footer class="footer">
            <p>UNIVESP PI 2024</p>
            <p><a href="https://github.com/UNIVESP-PI-BAURU/PI24b.git" target="_blank">https://github.com/UNIVESP-PI-BAURU/PI24b.git</a></p>
        </footer>
  
    </body>
</html>
