<?php
session_start();

require_once '../conexao.php'; 

// Coleta dos filtros
$cidade = isset($_POST['cidade']) ? trim($_POST['cidade']) : '';
$estado = isset($_POST['estado']) ? trim($_POST['estado']) : '';
$idioma = isset($_POST['idioma']) ? trim($_POST['idioma']) : '';

// Bloco try-catch para capturar erros
try {
    // Query corrigida
    $sql = "SELECT a.nome, a.cidade, a.estado, ia.idioma
            FROM Alunos a
            LEFT JOIN IdiomaAluno ia ON a.id_aluno = ia.id_aluno
            WHERE 1=1";

    // Adiciona os filtros à query
    if (!empty($cidade)) {
        $sql .= " AND LOWER(TRIM(a.cidade)) LIKE LOWER(TRIM(:cidade))";
    }
    if (!empty($estado)) {
        $sql .= " AND LOWER(TRIM(a.estado)) LIKE LOWER(TRIM(:estado))";
    }
    if (!empty($idioma)) {
        $sql .= " AND LOWER(TRIM(ia.idioma)) LIKE LOWER(TRIM(:idioma))";
    }

    // Prepara a query
    $stmt = $conn->prepare($sql);

    // Bind dos parâmetros
    if (!empty($cidade)) {
        $stmt->bindValue(':cidade', "%$cidade%", PDO::PARAM_STR);
    }
    if (!empty($estado)) {
        $stmt->bindValue(':estado', "%$estado%", PDO::PARAM_STR);
    }
    if (!empty($idioma)) {
        $stmt->bindValue(':idioma', "%$idioma%", PDO::PARAM_STR);
    }

    // Executa a consulta
    $stmt->execute();
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Verifica se há resultados
    if ($resultados) {
        $_SESSION['alunos_resultados'] = $resultados;
        header("Location: resultado_alunos.php");
        exit();
    } else {
        $_SESSION['erro_consulta'] = "Não conseguimos encontrar registros, tente novamente.";
        header("Location: resultado_alunos.php");
        exit();
    }

} catch (PDOException $e) {
    // Captura o erro e exibe para debug
    die("Erro na consulta: " . $e->getMessage());
}

// Fecha a conexão
$conn = null;
?>
