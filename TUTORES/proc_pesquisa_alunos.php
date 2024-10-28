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

// Coleta os dados do formulário e remove espaços em excesso
$cidade = isset($_POST['cidade']) ? trim($_POST['cidade']) : '';
$estado = isset($_POST['estado']) ? trim($_POST['estado']) : '';
$idioma = isset($_POST['idioma']) ? trim($_POST['idioma']) : '';

// Cria a consulta SQL inicial
$sql = "
    SELECT a.nome, a.cidade, a.estado, ia.idioma 
    FROM Alunos a
    JOIN IdiomaAluno ia ON a.id_aluno = ia.id_aluno
    WHERE 1=1
";

// Adiciona filtros dinamicamente, aplicando `LOWER` e `TRIM` para comparação flexível
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
$resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Verifica se houve resultados e armazena na sessão
if ($resultados) {
    $_SESSION['alunos_resultados'] = $resultados;
    header("Location: resultado_alunos.php");
    exit();
} else {
    $_SESSION['erro_consulta'] = "Nenhum aluno encontrado com os critérios fornecidos.";
    header("Location: resultado_alunos.php");
    exit();
}

// Fecha a conexão
$conn = null;
?>
