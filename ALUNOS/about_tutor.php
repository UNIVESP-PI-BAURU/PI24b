<?php 
session_start();

// Verifica se o ID do tutor foi passado na URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: pesquisa_tutores.php");
    exit();
}

// Obtém o ID do tutor e a ID do aluno que está logado
$id_tutor = intval($_GET['id']);
$id_aluno = $_SESSION['id_aluno'] ?? null;

require_once '../conexao.php';

try {
    // Recupera as informações do tutor
    $stmt = $conn->prepare("SELECT * FROM Tutores WHERE id_tutor = :id");
    $stmt->execute(['id' => $id_tutor]);
    $tutor = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verifica se o tutor foi encontrado
    if (!$tutor) {
        header("Location: pesquisa_tutores.php");
        exit();
    }

    // Recupera os idiomas do tutor
    $stmt_idiomas = $conn->prepare("SELECT idioma FROM IdiomaTutor WHERE tutor_id = :id");
    $stmt_idiomas->execute(['id' => $id_tutor]);
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
        <a href="./index.php">Home</a>
        <a href="./sobre_nos.php">Sobre nós</a>
        <a href="./dashboard.php">Dashboard</a>
    </nav>

    <!-- Detalhes do Tutor -->
    <div class="about-section">
        <h2>Perfil de Tutor: <?php echo htmlspecialchars($tutor['nome']); ?></h2>
        <p><strong>ID:</strong> <?php echo htmlspecialchars($tutor['id_tutor']); ?></p>

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

        <!-- Campos ocultos para armazenar IDs do tutor e do aluno -->
        <input type="hidden" id="id_tutor" value="<?php echo htmlspecialchars($id_tutor); ?>">
        <input type="hidden" id="id_aluno" value="<?php echo htmlspecialchars($id_aluno); ?>">
    </div>

    <!-- Funcionalidades de interação -->
    <div class="interactions">
        <h3>Interação</h3>
        <button>Enviar Mensagem</button>
        <button>Adicionar aos Favoritos</button>
    </div>

    <!-- Rodapé -->
    <div class="footer">
        UNIVESP PI 2024
    </div>
</body>
</html>
