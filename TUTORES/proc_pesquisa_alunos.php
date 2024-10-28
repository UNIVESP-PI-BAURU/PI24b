<?php
session_start();

// Verifica se o usuário está logado e redireciona para o login se não estiver
if (!isset($_SESSION['id_aluno']) && !isset($_SESSION['id_tutor'])) {
    header("Location: ../login.php");
    exit();
}

require_once '../conexao.php'; // Inclui a conexão com o banco de dados

// Identifica o tipo de usuário e obtém o ID
$tipo_usuario = isset($_SESSION['id_aluno']) ? 'aluno' : 'tutor';
$id_usuario = $_SESSION['id_' . $tipo_usuario];

// Coleta os dados do formulário
$cidades = isset($_POST['cidades']) ? $_POST['cidades'] : [];
$estados = isset($_POST['estados']) ? $_POST['estados'] : [];
$idiomas = isset($_POST['idiomas']) ? $_POST['idiomas'] : [];

// Configurações de paginação
$limite = 10; // Número de resultados por página
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($pagina - 1) * $limite;

// Cria a consulta SQL inicial
$sql = "
    SELECT a.nome, a.cidade, a.estado, ia.idioma 
    FROM Alunos a
    JOIN IdiomaAluno ia ON a.id_aluno = ia.id_aluno
    WHERE 1=1
";

// Adiciona filtros dinamicamente
if (!empty($cidades)) {
    $sql .= " AND a.cidade IN (" . implode(',', array_fill(0, count($cidades), '?')) . ")";
}
if (!empty($estados)) {
    $sql .= " AND a.estado IN (" . implode(',', array_fill(0, count($estados), '?')) . ")";
}
if (!empty($idiomas)) {
    $sql .= " AND ia.idioma IN (" . implode(',', array_fill(0, count($idiomas), '?')) . ")";
}

// Adiciona a cláusula LIMIT para paginação
$sql .= " LIMIT :limite OFFSET :offset";

// Prepara e executa a consulta
$stmt = $conn->prepare($sql);

// Adiciona os parâmetros de cidade
if (!empty($cidades)) {
    foreach ($cidades as $cidade) {
        $stmt->bindValue($i++, $cidade);
    }
}

// Adiciona os parâmetros de estado
if (!empty($estados)) {
    foreach ($estados as $estado) {
        $stmt->bindValue($i++, $estado);
    }
}

// Adiciona os parâmetros de idioma
if (!empty($idiomas)) {
    foreach ($idiomas as $idioma) {
        $stmt->bindValue($i++, $idioma);
    }
}

$stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

$stmt->execute();
$resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Contar total de resultados para a paginação
$total_sql = "SELECT COUNT(*) FROM Alunos a JOIN IdiomaAluno ia ON a.id_aluno = ia.id_aluno WHERE 1=1";
if (!empty($cidades)) {
    $total_sql .= " AND a.cidade IN (" . implode(',', array_fill(0, count($cidades), '?')) . ")";
}
if (!empty($estados)) {
    $total_sql .= " AND a.estado IN (" . implode(',', array_fill(0, count($estados), '?')) . ")";
}
if (!empty($idiomas)) {
    $total_sql .= " AND ia.idioma IN (" . implode(',', array_fill(0, count($idiomas), '?')) . ")";
}

$total_stmt = $conn->prepare($total_sql);

// Adiciona os parâmetros de cidade
if (!empty($cidades)) {
    foreach ($cidades as $cidade) {
        $total_stmt->bindValue($i++, $cidade);
    }
}

// Adiciona os parâmetros de estado
if (!empty($estados)) {
    foreach ($estados as $estado) {
        $total_stmt->bindValue($i++, $estado);
    }
}

// Adiciona os parâmetros de idioma
if (!empty($idiomas)) {
    foreach ($idiomas as $idioma) {
        $total_stmt->bindValue($i++, $idioma);
    }
}

$total_stmt->execute();
$total_resultados = $total_stmt->fetchColumn();
$total_paginas = ceil($total_resultados / $limite);

// Verifica se houve resultados e armazena na sessão
if ($resultados) {
    $_SESSION['alunos_resultados'] = $resultados;
    $_SESSION['total_paginas'] = $total_paginas; // Armazenar total de páginas na sessão
    header("Location: resultado_alunos.php?page=$pagina");
    exit();
} else {
    $_SESSION['erro_consulta'] = "Nenhum aluno encontrado com os critérios fornecidos.";
    header("Location: resultado_alunos.php");
    exit();
}

// Fecha a conexão
$conn = null;
?>
