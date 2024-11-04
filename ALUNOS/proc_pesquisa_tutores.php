<?php 
session_start(); // Inicia a sessão
require_once '../conexao.php';

// Verifica se o tutor está logado
if (!isset($_SESSION['id_tutor'])) {
    error_log("Tentativa de acesso não autorizada à pesquisa de tutores.");
    header("Location: ../login.php");
    exit();
}

// Coleta dos filtros
$cidade = isset($_POST['cidade']) ? trim($_POST['cidade']) : '';
$estado = isset($_POST['estado']) ? trim($_POST['estado']) : '';
$idioma = isset($_POST['idioma']) ? trim($_POST['idioma']) : '';
$id_tutor = $_SESSION['id_tutor']; // Captura a ID do tutor diretamente da sessão
$tipo_usuario = 'tutor'; // Definindo tipo de usuário
$tipo_conversor = 'tutor'; // Definindo tipo de conversor

// Debug: Exibir filtros recebidos
error_log("Filtros recebidos: cidade = $cidade, estado = $estado, idioma = $idioma, id_tutor = $id_tutor");

// Verifica se pelo menos um dos filtros foi preenchido
if (empty($cidade) && empty($estado) && empty($idioma)) {
    $_SESSION['erro_consulta'] = "É necessário preencher pelo menos um critério de pesquisa.";
    header("Location: resultado_tutores.php");
    exit();
}

try {
    // Inicializa um array para armazenar resultados
    $resultados = [];

    // Construir a consulta dependendo dos filtros preenchidos
    $sql = "SELECT t.id AS id_tutor, t.nome, t.cidade, t.estado, GROUP_CONCAT(it.idioma SEPARATOR ', ') AS idiomas
            FROM Tutores t
            INNER JOIN IdiomaTutor it ON t.id = it.id_tutor
            WHERE 1=1"; // Para facilitar a adição de condições

    if (!empty($idioma)) {
        $sql .= " AND LOWER(TRIM(it.idioma)) LIKE LOWER(TRIM(:idioma))";
    }
    if (!empty($cidade)) {
        $sql .= " AND LOWER(TRIM(t.cidade)) LIKE LOWER(TRIM(:cidade))";
    }
    if (!empty($estado)) {
        $sql .= " AND LOWER(TRIM(t.estado)) LIKE LOWER(TRIM(:estado))";
    }

    $sql .= " GROUP BY t.id"; // Agrupa os resultados para evitar duplicação

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
    header("Location: resultado_tutores.php");
    exit();
}

// Armazena resultados na sessão para exibição
$_SESSION['resultados_tutores'] = $resultados;

// Redireciona para a página de resultados
header("Location: resultado_tutores.php");
exit();
