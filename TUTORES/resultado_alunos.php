<?php
session_start();

// Verifica se o usuário está logado e redireciona para o login se não estiver
if (!isset($_SESSION['id_aluno']) && !isset($_SESSION['id_tutor'])) {
    header("Location: ../login.php");
    exit();
}

require_once '../conexao.php'; // Inclui a conexão com o banco de dados

// Coleta os dados do formulário
$cidade = isset($_POST['cidade']) ? trim($_POST['cidade']) : '';
$estado = isset($_POST['estado']) ? trim($_POST['estado']) : '';
$idioma = isset($_POST['idioma']) ? trim($_POST['idioma']) : '';
$erro_consulta = null;

// Cria a consulta SQL para alunos
$sql = "SELECT a.nome, a.cidade, a.estado, ia.idioma 
        FROM Alunos a
        LEFT JOIN IdiomaAluno ia ON a.id_aluno = ia.id_aluno 
        WHERE 1=1";

// Adiciona condições baseadas nos filtros fornecidos
if (!empty($cidade)) {
    $sql .= " AND LOWER(TRIM(a.cidade)) LIKE LOWER(TRIM(:cidade))";
}
if (!empty($estado)) {
    $sql .= " AND LOWER(TRIM(a.estado)) LIKE LOWER(TRIM(:estado))";
}
if (!empty($idioma)) {
    $sql .= " AND LOWER(TRIM(ia.idioma)) LIKE LOWER(TRIM(:idioma))";
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
$alunos_resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Verifica se há resultados
if (empty($alunos_resultados)) {
    $erro_consulta = "Não conseguimos encontrar registros, tente novamente.";
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados da Pesquisa de Alunos</title>
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
            <h2>Resultados da Pesquisa de Alunos</h2>

            <?php if ($erro_consulta): ?>
                <p><?php echo htmlspecialchars($erro_consulta); ?></p>
                <a href="pesquisa_alunos.php"><button>Voltar</button></a>
            <?php else: ?>
                <ul>
                    <?php foreach ($alunos_resultados as $aluno): ?>
                        <li>
                            <?php echo htmlspecialchars($aluno['nome']) . " - " .
                                       htmlspecialchars($aluno['cidade']) . ", " . 
                                       htmlspecialchars($aluno['estado']) . " (" . 
                                       htmlspecialchars($aluno['idioma']) . ")"; ?>
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
