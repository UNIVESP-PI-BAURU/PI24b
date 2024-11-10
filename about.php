<?php
session_start();
require_once 'conexao.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['id_usuario']) || !isset($_SESSION['tipo_usuario'])) {
    header("Location: login.php");
    exit();
}

// Obtém o ID do perfil visitado da URL
$id_perfil = isset($_GET['id']) ? intval($_GET['id']) : null;
if (!$id_perfil) {
    header("Location: dashboard.php"); // Redireciona se o ID não for válido
    exit();
}

// Verifica o tipo do usuário logado
$tipo_usuario_logado = $_SESSION['tipo_usuario'];

// Define a tabela de pesquisa com base no tipo do usuário logado
$tabela_perfil = ($tipo_usuario_logado === 'aluno') ? 'Tutores' : 'Alunos';

// Consulta os dados do usuário do perfil
$sql = "SELECT id, nome, foto_perfil, cidade, estado, idiomas, biografia, data_nascimento FROM $tabela_perfil WHERE id = :id";
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
    <script>
        function curtirPerfil(id_perfil) {
            fetch('curtir.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id_perfil })
            })
            .then(response => response.json())
            .then(data => {
                if (data.sucesso) {
                    alert("Você curtiu este perfil!");
                } else {
                    alert("Erro ao curtir perfil.");
                }
            })
            .catch(error => console.error("Erro:", error));
        }
    </script>
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
        <section class="signup-section">
            <h3>Mais sobre <?php echo htmlspecialchars($perfil_usuario['nome']); ?></h3>
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
                    <p><strong>Data de Nascimento:</strong> <?php echo htmlspecialchars(date('d/m/Y', strtotime($perfil_usuario['data_nascimento']))); ?></p>
                </div>
            </div>

            <div class="interaction-buttons">
                <!-- Botão para iniciar chat -->
                <?php if ($tipo_usuario_logado === 'aluno'): ?>
                    <button onclick="window.location.href='chat.php?id=<?php echo $perfil_usuario['id']; ?>'">Iniciar Chat</button>
                <?php endif; ?>

                <!-- Botão para contratar ou agendar aula -->
                <?php if ($tipo_usuario_logado === 'aluno'): ?>
                    <button onclick="window.location.href='agendar.php?id=<?php echo $perfil_usuario['id']; ?>'">Contratar/Agendar Aula</button>
                <?php endif; ?>

                <?php if ($tipo_usuario_logado === 'aluno'): ?>
                    <!-- Botão de curtir, agora com função AJAX -->
                    <button onclick="curtirPerfil(<?php echo $perfil_usuario['id']; ?>)">Curtir</button>
                <?php endif; ?>
            </div>

            <br><br>
            <!-- Botão para retornar à Dashboard -->
            <button onclick="window.location.href='dashboard.php'">Retornar à Dashboard</button>

        </section>
    </main>

    <!-- Rodapé -->
    <footer class="footer">
        <p>UNIVESP PI 2024</p>
        <p><a href="https://github.com/UNIVESP-PI-BAURU/PI24b.git" target="_blank">https://github.com/UNIVESP-PI-BAURU/PI24b.git</a></p>
    </footer>
    
</body>
</html>
