<?php
session_start();

// Verifica se o usuário está logado e redireciona para login se não estiver
if (!isset($_SESSION['id_aluno']) && !isset($_SESSION['id_tutor'])) {
    header("Location: ../login.php");
    exit();
}

require_once '../conexao.php'; // Inclui a conexão com o banco de dados

// Dados da sessão
$cidade = $_POST['cidade'] ?? '';
$estado = $_POST['estado'] ?? '';
$idioma = $_POST['idioma'] ?? '';
$erro_consulta = null;

// Coletar dados da pesquisa
$cidade = trim($cidade);
$estado = trim($estado);
$idioma = trim($idioma);

// Criar a consulta
$sql = "SELECT t.nome, t.cidade, t.estado, it.idioma 
        FROM Tutores t
        JOIN IdiomaTutor it ON t.id_tutor = it.id_tutor WHERE 1=1";

if (!empty($cidade)) {
    $sql .= " AND LOWER(TRIM(t.cidade)) LIKE LOWER(TRIM(:cidade))";
}
if (!empty($estado)) {
    $sql .= " AND LOWER(TRIM(t.estado)) LIKE LOWER(TRIM(:estado))";
}
if (!empty($idioma)) {
    $sql .= " AND LOWER(TRIM(it.idioma)) LIKE LOWER(TRIM(:idioma))";
}

// Prepara e executa a consulta
$stmt = $conn->prepare($sql);
if (!empty($cidade)) {
    $stmt->bindValue(':cidade', "%$cidade%", PDO::PARAM_STR);
}
if (!empty($estado)) {
    $stmt->bindValue(':estado', "%$estado%", PDO::PARAM_STR);
}
if (!empty($idioma)) {
    $stmt->bindValue(':idioma', "%$idioma%", PDO::PARAM_STR);
}
$stmt->execute();
$tutores_resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Verifica se houve resultados
if (empty($tutores_resultados)) {
    $erro_consulta = "Não conseguimos encontrar registros, tente novamente.";
}

// Limpa a sessão
unset($_SESSION['tutores_resultados'], $_SESSION['erro_consulta']);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados da Pesquisa de Tutores</title>
    <link rel="stylesheet" href="ASSETS/CSS/style.css">
</head>
<body>
    <header class="header">
        <img src="ASSETS/IMG/capa.png" alt="Imagem de Capa">
    </header>
    <nav class="navbar">
        <a href="./index.php">Home</a>
        <a href="./sobre_nos.php">Sobre nós</a>
        <a href="./cadastro.html">Cadastro</a>
    </nav>
    <div class="main-content">
        <div class="signup-section">
            <h2>Resultados da Pesquisa de Tutores</h2>

            <?php if ($erro_consulta): ?>
                <p><?php echo htmlspecialchars($erro_consulta); ?></p>
                <a href="pesquisa_tutores.php">Voltar</a>
            <?php else: ?>
                <ul>
                    <?php foreach ($tutores_resultados as $tutor): ?>
                        <li><?php echo htmlspecialchars($tutor['nome']) . " - " . 
                                   htmlspecialchars($tutor['cidade']) . ", " . 
                                   htmlspecialchars($tutor['estado']) . " (" . 
                                   htmlspecialchars($tutor['idioma']) . ")"; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
    <footer class="footer">
        <p>UNIVESP PI 2024</p>
    </footer>
</body>
</html>
