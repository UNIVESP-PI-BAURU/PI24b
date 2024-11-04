<?php 
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['id_aluno']) && !isset($_SESSION['id_tutor'])) {
    header("Location: ../index.php");
    exit();
}

// Verifica se o ID do tutor foi passado na URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: pesquisa_tutores.php");
    exit();
}

// Obtém o ID do tutor e a ID do tutor que está logado
$id_tutor_exibido = intval($_GET['id']);
$id_tutor_logado = $_SESSION['id_tutor'] ?? null;

require_once '../conexao.php'; // Certifique-se de que o caminho para o arquivo de conexão está correto

try {
    // Recupera as informações do tutor
    $stmt = $conn->prepare("SELECT * FROM Tutores WHERE id = :id");
    $stmt->execute(['id' => $id_tutor_exibido]);
    $tutor = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verifica se o tutor foi encontrado
    if (!$tutor) {
        header("Location: pesquisa_tutores.php");
        exit();
    }

    // Recupera os idiomas do tutor (ajuste o nome da tabela conforme necessário)
    $stmt_idiomas = $conn->prepare("SELECT idioma FROM IdiomaTutor WHERE id_tutor = :id");
    $stmt_idiomas->execute(['id' => $id_tutor_exibido]);
    $idiomas = $stmt_idiomas->fetchAll(PDO::FETCH_COLUMN);

    // Debug: Registrar informações do tutor e dos idiomas
    error_log("Tutor encontrado: " . print_r($tutor, true));
    error_log("Idiomas do tutor: " . print_r($idiomas, true));
} catch (PDOException $e) {
    echo "Erro na consulta: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Tutor - <?php echo htmlspecialchars($tutor['nome']); ?></title>
    <link rel="stylesheet" href="ASSETS/CSS/style.css">
</head>
<body>
    <!-- Cabeçalho -->
    <div class="header">
        <img src="ASSETS/IMG/capa.png" alt="Imagem de Capa">
    </div>

    <!-- Navegação -->
    <nav class="navbar">
        <a href="../index.php">Home</a>
        <a href="../sobre_nos.php">Sobre nós</a>
        <a href="<?php echo isset($_SESSION['id_tutor']) ? './dashboard_tutor.php' : './dashboard_aluno.php'; ?>">Dashboard</a>
        <a href="../logout.php">Logout</a>
    </nav>

    <!-- Detalhes do Tutor -->
    <div class="about-section">
        <h2>Perfil de Tutor: <?php echo htmlspecialchars($tutor['nome']); ?></h2>
        <p><strong>ID:</strong> <?php echo htmlspecialchars($tutor['id']); ?></p>

        <!-- Exibe a foto de perfil, se disponível -->
        <div class="foto-perfil">
            <div class="foto-moldura-perfil">
                <?php if (!empty($tutor['foto_perfil'])): ?>
                    <img src="<?php echo htmlspecialchars($tutor['foto_perfil']); ?>" alt="Foto de Perfil" class="avatar-perfil">
                <?php else: ?>
                    <p>Sem foto</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="info-usuario">
            <p><strong>Email:</strong> <?php echo htmlspecialchars($tutor['email']); ?></p>
            <p><strong>Cidade/Estado:</strong> <?php echo htmlspecialchars($tutor['cidade']) . ', ' . htmlspecialchars($tutor['estado']); ?></p>
            <p><strong>Data de Nascimento:</strong> <?php echo !empty($tutor['data_nascimento']) ? htmlspecialchars($tutor['data_nascimento']) : 'Não informado'; ?></p>
            <p><strong>Idiomas:</strong> <?php echo implode(', ', array_map('htmlspecialchars', $idiomas)); ?></p>
            <p><strong>Biografia:</strong> <?php echo htmlspecialchars($tutor['biografia']); ?></p>
        </div>
        <br><br>

        <!-- Funcionalidades de interação -->
        <div class="interactions">
            <h3>Interação</h3>
            <div class="chat">
                <!-- Botão Chat -->
                <input type="hidden" id="id_usuario" value="<?php echo $id_tutor_exibido; ?>" />
                <button type="button" class="chat-button" onclick="redirectToChat()">Chat</button>
                <script>
                function redirectToChat() {
                    var idUsuario = document.getElementById('id_usuario').value;
                    window.location.href = 'chat.php?id=' + idUsuario;
                }
                </script>
            </div>
        </div>
        
        <br><br>
        <!-- Campos ocultos para armazenar IDs do tutor e do tutor logado -->
        <input type="hidden" id="id_tutor_exibido" value="<?php echo htmlspecialchars($id_tutor_exibido); ?>">
        <input type="hidden" id="id_tutor_logado" value="<?php echo htmlspecialchars($id_tutor_logado); ?>">
    </div>

    <!-- Rodapé -->
    <div class="footer">
        UNIVESP PI 2024
    </div>
</body>
</html>
