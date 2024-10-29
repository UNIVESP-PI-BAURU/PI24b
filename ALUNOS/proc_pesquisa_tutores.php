<?php
session_start();

// Verifica se o usuário está logado e redireciona para login se não estiver
if (!isset($_SESSION['id_aluno']) && !isset($_SESSION['id_tutor'])) {
    header("Location: ../login.php");
    exit();
}

require_once '../conexao.php'; // Inclui a conexão com o banco de dados

// Coletar os dados do formulário
$cidade = isset($_POST['cidade']) ? trim($_POST['cidade']) : '';
$estado = isset($_POST['estado']) ? trim($_POST['estado']) : '';
$idioma = isset($_POST['idioma']) ? trim($_POST['idioma']) : '';

// Criar a consulta
$sql = "SELECT t.nome, t.cidade, t.estado, it.idioma 
        FROM Tutores t
        LEFT JOIN IdiomaTutor it ON t.id_tutor = it.id_tutor 
        WHERE 1=1";

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
$resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Verifica se houve resultados e armazena na sessão
if ($resultados) {
    $_SESSION['tutores_resultados'] = $resultados;
    header("Location: resultado_tutores.php");
    exit();
} else {
    $_SESSION['erro_consulta'] = "Não conseguimos encontrar registros, tente novamente.";
    header("Location: resultado_tutores.php");
    exit();
}

// Fecha a conexão
$conn = null;
?>
