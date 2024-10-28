<?php
session_start();

// Verifica se o usuário está logado e redireciona para login se não estiver
if (!isset($_SESSION['id_aluno']) && !isset($_SESSION['id_tutor'])) {
    header("Location: ../login.php");
    exit();
}

require_once '../conexao.php'; // Inclui a conexão com o banco de dados

// Define o tipo de usuário e busca os dados
$tipo_usuario = isset($_SESSION['id_aluno']) ? 'aluno' : 'tutor';
$id_usuario = $_SESSION['id_' . $tipo_usuario];

// Coletar os dados do formulário
$cidade = isset($_POST['cidade']) ? trim($_POST['cidade']) : '';
$estado = isset($_POST['estado']) ? trim($_POST['estado']) : '';
$idioma = isset($_POST['idioma']) ? trim($_POST['idioma']) : '';

// Configurações de paginação
$limite = 10; // Número de resultados por página
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($pagina - 1) * $limite;

// Criar a consulta
$sql = "SELECT t.nome, t.cidade, t.estado, it.idioma 
        FROM Tutores t
        JOIN IdiomaTutor it ON t.id_tutor = it.id_tutor WHERE 1=1";

// Adicionar condições baseadas nos filtros fornecidos
if (!empty($cidade)) {
    $sql .= " AND LOWER(TRIM(t.cidade)) LIKE LOWER(TRIM(:cidade))";
}
if (!empty($estado)) {
    $sql .= " AND LOWER(TRIM(t.estado)) LIKE LOWER(TRIM(:estado))";
}
if (!empty($idioma)) {
    $sql .= " AND LOWER(TRIM(it.idioma)) LIKE LOWER(TRIM(:idioma))";
}

// Adiciona a cláusula LIMIT para paginação
$sql .= " LIMIT :limite OFFSET :offset";

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
$stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

$stmt->execute();
$resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Contar total de resultados para a paginação
$total_sql = "SELECT COUNT(*) FROM Tutores t JOIN IdiomaTutor it ON t.id_tutor = it.id_tutor WHERE 1=1";
if (!empty($cidade)) {
    $total_sql .= " AND LOWER(TRIM(t.cidade)) LIKE LOWER(TRIM(:cidade))";
}
if (!empty($estado)) {
    $total_sql .= " AND LOWER(TRIM(t.estado)) LIKE LOWER(TRIM(:estado))";
}
if (!empty($idioma)) {
    $total_sql .= " AND LOWER(TRIM(it.idioma)) LIKE LOWER(TRIM(:idioma))";
}

$total_stmt = $conn->prepare($total_sql);
if (!empty($cidade)) {
    $total_stmt->bindValue(':cidade', "%$cidade%", PDO::PARAM_STR);
}
if (!empty($estado)) {
    $total_stmt->bindValue(':estado', "%$estado%", PDO::PARAM_STR);
}
if (!empty($idioma)) {
    $total_stmt->bindValue(':idioma', "%$idioma%", PDO::PARAM_STR);
}

$total_stmt->execute();
$total_resultados = $total_stmt->fetchColumn();
$total_paginas = ceil($total_resultados / $limite);

// Verifica se houve resultados e armazena na sessão
if ($resultados) {
    $_SESSION['tutores_resultados'] = $resultados;
    $_SESSION['total_paginas'] = $total_paginas; // Armazenar total de páginas na sessão
    header("Location: resultado_tutores.php?page=$pagina");
    exit();
} else {
    $_SESSION['erro_consulta'] = "Nenhum tutor encontrado com os critérios fornecidos.";
    header("Location: resultado_tutores.php");
    exit();
}

// Fecha a conexão
$conn = null;
?>
