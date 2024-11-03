<?php 
session_start();
require_once '../conexao.php';

// Verifica se o aluno está logado
if (!isset($_SESSION['id_aluno'])) {
    error_log("Tentativa de acesso não autorizada à pesquisa de alunos.");
    header("Location: ../login.php");
    exit();
}

// Coleta dos filtros
$cidade = isset($_POST['cidade']) ? trim($_POST['cidade']) : '';
$estado = isset($_POST['estado']) ? trim($_POST['estado']) : '';
$idioma = isset($_POST['idioma']) ? trim($_POST['idioma']) : '';
$id_aluno = $_SESSION['id_aluno']; // Captura a ID do aluno diretamente da sessão
$tipo_usuario = 'aluno'; // Definindo tipo de usuário
$tipo_conversor = 'aluno'; // Definindo tipo de conversor

// Debug: Exibir filtros recebidos
error_log("Filtros recebidos: cidade = $cidade, estado = $estado, idioma = $idioma, id_aluno = $id_aluno");

// Verifica se pelo menos um dos filtros foi preenchido
if (empty($cidade) && empty($estado) && empty($idioma)) {
    $_SESSION['erro_consulta'] = "É necessário preencher pelo menos um critério de pesquisa.";
    header("Location: resultado_alunos.php");
    exit();
}

try {
    // Inicializa um array para armazenar resultados
    $resultados = [];

    // Construir a consulta dependendo dos filtros preenchidos
    $sql = "SELECT a.id AS id_aluno, a.nome, a.cidade, a.estado, GROUP_CONCAT(ia.idioma SEPARATOR ', ') AS idiomas
            FROM Alunos a
            INNER JOIN IdiomaAluno ia ON a.id = ia.id_aluno
            WHERE 1=1"; // Para facilitar a adição de condições

    if (!empty($idioma)) {
        $sql .= " AND LOWER(TRIM(ia.idioma)) LIKE LOWER(TRIM(:idioma))";
    }
    if (!empty($cidade)) {
        $sql .= " AND LOWER(TRIM(a.cidade)) LIKE LOWER(TRIM(:cidade))";
    }
    if (!empty($estado)) {
        $sql .= " AND LOWER(TRIM(a.estado)) LIKE LOWER(TRIM(:estado))";
    }

    $sql .= " GROUP BY a.id"; // Agrupa os resultados para evitar duplicação

    $stmt = $conn->prepare($sql);

    // Bind dos valores
    if (!empty($idioma)) {
        $stmt->bindValue(':idioma', "%$idioma%", PDO::PARAM_STR);
    }
    if (!empty($cidade)) {
        $stmt->bindValue(':cidade', "%$cidade%", PDO::PARAM_STR);
    }
    if (!empty($estado)) {
        $stmt->bindValue(':estado', "%$estado%", PDO::PARAM_STR);
    }

    // Executa a consulta
    $stmt->execute();

    // Armazena os resultados
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Debug: Exibir resultados obtidos
    error_log("Resultados obtidos: " . print_r($resultados, true));

} catch (Exception $e) {
    // Captura erros de execução
    error_log("Erro: " . $e->getMessage());
    $_SESSION['erro_consulta'] = "Erro ao realizar a consulta.";
    header("Location: resultado_alunos.php");
    exit();
}

// Armazena resultados na sessão para exibição
$_SESSION['resultados_alunos'] = $resultados;

// Redireciona para a página de resultados
header("Location: resultado_alunos.php");
exit();
