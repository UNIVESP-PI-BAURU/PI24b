<?php
session_start(); // Inicia a sessão

// Verifica se o usuário está logado e redireciona para login se não estiver
if (!isset($_SESSION['id']) || !isset($_SESSION['tipo'])) {
    header("Location: login.php"); // Redireciona para login
    exit(); // Evita que o código continue
}

require_once 'conexao.php'; // Inclui a conexão com o banco

// Verifica a conexão com o banco de dados
if (!$conn) {
    die("Falha na conexão com o banco de dados.");
}

// Define o tipo de usuário e busca os dados
$tipo_usuario = $_SESSION['tipo']; // Pode ser 'aluno' ou 'tutor'
$id_usuario = $_SESSION['id']; // ID comum para todos os tipos
$tabela_usuario = ($tipo_usuario === 'aluno') ? 'Alunos' : 'Tutores';

// Exibindo dados de depuração
var_dump($tipo_usuario, $id_usuario); // Verificando as variáveis de sessão

// Consulta os dados do usuário
$sql = "SELECT nome, foto_perfil, cidade, estado, biografia, idiomas FROM $tabela_usuario WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $id_usuario);
$stmt->execute();
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

// Exibindo dados do usuário para depuração
var_dump($usuario); // Verificando os dados retornados

// Se o usuário não for encontrado, redireciona para o login
if (!$usuario) {
    header("Location: login.php");
    exit();
}

// Processa a atualização do perfil
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Coleta os dados do formulário
    $nome = $_POST['nome'];
    $biografia = $_POST['biografia'];
    $idiomas = $_POST['idiomas'];
    $cidade = $_POST['cidade'];
    $estado = $_POST['estado'];

    // Se houver uma nova foto, trata o upload
    if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] == 0) {
        $foto_perfil = 'uploads/' . basename($_FILES['foto_perfil']['name']);
        move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $foto_perfil);
    } else {
        $foto_perfil = $usuario['foto_perfil']; // Mantém a foto antiga, se não houver upload
    }

    // Atualiza os dados no banco
    $sql_update = "UPDATE $tabela_usuario SET nome = :nome, biografia = :biografia, idiomas = :idiomas, cidade = :cidade, estado = :estado, foto_perfil = :foto_perfil WHERE id = :id";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bindParam(':nome', $nome);
    $stmt_update->bindParam(':biografia', $biografia);
    $stmt_update->bindParam(':idiomas', $idiomas);
    $stmt_update->bindParam(':cidade', $cidade);
    $stmt_update->bindParam(':estado', $estado);
    $stmt_update->bindParam(':foto_perfil', $foto_perfil);
    $stmt_update->bindParam(':id', $id_usuario);

    if ($stmt_update->execute()) {
        header("Location: perfil.php"); // Redireciona para a página de perfil após atualização
        exit();
    } else {
        echo "Erro ao atualizar os dados.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil Completo</title>
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

    <!-- Conteúdo Principal -->
    <main class="main-content">
        <section class="perfil-section">
            <h2>Perfil Completo</h2>

            <!-- Exibição dos dados -->
            <div class="perfil-info">
                <img src="<?php echo htmlspecialchars($usuario['foto_perfil']) ?>" alt="Foto de perfil" class="foto-perfil">
                <p><strong>Nome:</strong> <?php echo htmlspecialchars($usuario['nome']); ?></p>
                <p><strong>Biografia:</strong> <?php echo nl2br(htmlspecialchars($usuario['biografia'])); ?></p>
                <p><strong>Idiomas:</strong> <?php echo htmlspecialchars($usuario['idiomas']); ?></p>
                <p><strong>Cidade:</strong> <?php echo htmlspecialchars($usuario['cidade']); ?></p>
                <p><strong>Estado:</strong> <?php echo htmlspecialchars($usuario['estado']); ?></p>
            </div>

            <!-- Formulário de edição -->
            <h3>Editar Perfil</h3>
            <form method="POST" enctype="multipart/form-data">
                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($usuario['nome']); ?>" required>

                <label for="biografia">Biografia:</label>
                <textarea id="biografia" name="biografia" required><?php echo htmlspecialchars($usuario['biografia']); ?></textarea>

                <label for="idiomas">Idiomas:</label>
                <input type="text" id="idiomas" name="idiomas" value="<?php echo htmlspecialchars($usuario['idiomas']); ?>" required>

                <label for="cidade">Cidade:</label>
                <input type="text" id="cidade" name="cidade" value="<?php echo htmlspecialchars($usuario['cidade']); ?>" required>

                <label for="estado">Estado:</label>
                <input type="text" id="estado" name="estado" value="<?php echo htmlspecialchars($usuario['estado']); ?>" required>

                <label for="foto_perfil">Foto de Perfil:</label>
                <input type="file" id="foto_perfil" name="foto_perfil">

                <button type="submit">Salvar Alterações</button>
            </form>
        </section>
    </main>

    <!-- Rodapé -->
    <footer class="footer">
        UNIVESP PI 2024
    </footer>

</body>
</html>
