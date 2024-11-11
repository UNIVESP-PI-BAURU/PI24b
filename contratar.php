<?php
session_start();
require_once 'conexao.php';

// Verifica se o usuário está logado e é um aluno
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo_usuario'] !== 'aluno') {
    header("Location: login.php");
    exit();
}

// Obtém o ID do tutor a ser contratado da URL
$id_tutor = isset($_GET['id']) ? intval($_GET['id']) : null;
if (!$id_tutor) {
    header("Location: dashboard.php"); // Redireciona se não houver tutor válido
    exit();
}

// Lógica para registrar a contratação
try {
    // Inserir o contrato na tabela
    $sql = "INSERT INTO Contratos (id_aluno, id_tutor, status, data_contrato) 
            VALUES (:id_aluno, :id_tutor, 'pendente', NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id_aluno', $_SESSION['id_usuario'], PDO::PARAM_INT);
    $stmt->bindParam(':id_tutor', $id_tutor, PDO::PARAM_INT);
    $stmt->execute();

    // Mensagem de sucesso
    $msg = "Você contratou o tutor com sucesso! O status está como pendente.";
} catch (PDOException $e) {
    // Se ocorrer algum erro
    $msg = "Erro ao contratar: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contratar</title>
    <link rel="stylesheet" href="ASSETS/CSS/style.css">
</head>
<body>

    <header class="header">
        <img src="ASSETS/IMG/capa.png" alt="Imagem de Capa">
    </header>

    <nav class="navbar">
        <button onclick="window.location.href='index.php'">Home</button>
        <button onclick="window.location.href='dashboard.php'">Dashboard</button>
        <button onclick="window.location.href='logout.php'">Logout</button>
    </nav>

    <!-- Conteúdo Principal -->
    <main class="main-content">
        <section class="signup-section">
            <h3><?php echo $msg; ?></h3>
            <button onclick="window.location.href='dashboard.php'">Voltar para a Dashboard</button>
        </section>
    </main>

    <!-- Rodapé -->
    <footer class="footer">
        <p>UNIVESP PI 2024</p>
        <p><a href="https://github.com/UNIVESP-PI-BAURU/PI24b.git" target="_blank">https://github.com/UNIVESP-PI-BAURU/PI24b.git</a></p>
    </footer>
    
</body>
</html>
