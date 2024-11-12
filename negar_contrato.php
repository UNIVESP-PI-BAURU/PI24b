<?php
session_start();
require_once 'conexao.php';

// Verifica se o usuário está logado e é um tutor
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo_usuario'] !== 'tutor') {
    header("Location: login.php");
    exit();
}

// Obtém o ID do contrato da URL
$id_contrato = isset($_GET['id']) ? intval($_GET['id']) : null;
if (!$id_contrato) {
    header("Location: dashboard.php"); // Redireciona se não houver contrato válido
    exit();
}

try {
    // Verifica se o contrato pertence ao tutor logado e está "pendente"
    $sql = "SELECT * FROM Contratos WHERE id = :id AND id_tutor = :id_tutor AND status = 'pendente'";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id_contrato, PDO::PARAM_INT);
    $stmt->bindParam(':id_tutor', $_SESSION['id_usuario'], PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        // Atualiza o status do contrato para "recusado"
        $updateSql = "UPDATE Contratos SET status = 'recusado' WHERE id = :id";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bindParam(':id', $id_contrato, PDO::PARAM_INT);
        $updateStmt->execute();

        // Mensagem de sucesso
        $msg = "Contrato recusado com sucesso!";
    } else {
        $msg = "Contrato não encontrado ou não está pendente.";
    }
} catch (PDOException $e) {
    // Se ocorrer um erro
    $msg = "Erro ao negar o contrato: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Negar Contrato</title>
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
