<?php
session_start();
require_once '../conexao.php';

// Coleta dos filtros
$cidade = isset($_POST['cidade']) ? trim($_POST['cidade']) : '';
$estado = isset($_POST['estado']) ? trim($_POST['estado']) : '';
$idioma = isset($_POST['idioma']) ? trim($_POST['idioma']) : '';

// Verifica se pelo menos um dos filtros foi preenchido
if (empty($cidade) && empty($estado) && empty($idioma)) {
    $_SESSION['erro_consulta'] = "É necessário preencher pelo menos um critério de pesquisa.";
    header("Location: resultado_alunos.php");
    exit();
}

// Debug: imprime os valores recebidos
error_log("Cidade: $cidade, Estado: $estado, Idioma: $idioma");

try {
    // Inicializa um array para armazenar resultados
    $resultados = [];
    
    // Construir a consulta dependendo dos filtros preenchidos
    $sql = "SELECT t.id AS id_tutor, t.nome, t.cidade, t.estado
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

    // Debug: imprime a consulta SQL gerada
    error_log("SQL: $sql");

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

    $stmt->execute();

    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Debug: imprime os resultados
    error_log("Resultados: " . print_r($resultados, true));

    // Verifica se há resultados
    if ($resultados) {
        $_SESSION['alunos_resultados'] = $resultados; 
        header("Location: resultado_tutores.php");
        exit();
    } else {
        $_SESSION['erro_consulta'] = "Não conseguimos encontrar registros, tente novamente.";
        header("Location: resultado_tutores.php");
        exit();
    }

} catch (PDOException $e) {
    error_log("Erro na consulta: " . $e->getMessage());
    $_SESSION['erro_consulta'] = "Ocorreu um erro ao processar sua solicitação. Tente novamente mais tarde.";
    header("Location: resultado_tutores.php");
    exit();
}

// Fecha a conexão
$conn = null;
?>
