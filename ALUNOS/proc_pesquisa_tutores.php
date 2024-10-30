<?php
session_start();

require_once '../conexao.php'; 

// Coleta dos filtros
$cidade = isset($_POST['cidade']) ? trim($_POST['cidade']) : '';
$estado = isset($_POST['estado']) ? trim($_POST['estado']) : '';
$idioma = isset($_POST['idioma']) ? trim($_POST['idioma']) : '';

// Verifica se pelo menos um critério foi preenchido
if (empty($cidade) && empty($estado) && empty($idioma)) {
    $_SESSION['erro_consulta'] = "É necessário preencher pelo menos um critério.";
    header("Location: resultado_tutores.php");
    exit();
}

try {
    // Inicializa um array para armazenar resultados
    $resultados = [];

    // Consulta para cidade
    if (!empty($cidade)) {
        $sqlCidade = "SELECT id_tutor, nome, cidade, estado FROM Tutores WHERE LOWER(TRIM(cidade)) LIKE LOWER(TRIM(:cidade))";
        $stmtCidade = $conn->prepare($sqlCidade);
        $stmtCidade->bindValue(':cidade', "%$cidade%", PDO::PARAM_STR);
        $stmtCidade->execute();
        $resultadosCidade = $stmtCidade->fetchAll(PDO::FETCH_ASSOC);
        $resultados = array_merge($resultados, $resultadosCidade);
    }

    // Consulta para estado
    if (!empty($estado)) {
        $sqlEstado = "SELECT id_tutor, nome, cidade, estado FROM Tutores WHERE LOWER(TRIM(estado)) LIKE LOWER(TRIM(:estado))";
        $stmtEstado = $conn->prepare($sqlEstado);
        $stmtEstado->bindValue(':estado', "%$estado%", PDO::PARAM_STR);
        $stmtEstado->execute();
        $resultadosEstado = $stmtEstado->fetchAll(PDO::FETCH_ASSOC);
        $resultados = array_merge($resultados, $resultadosEstado);
    }

    // Consulta para idioma
    if (!empty($idioma)) {
        // Obtemos os IDs dos tutores que possuem o idioma específico
        $sqlIdioma = "SELECT id_tutor FROM IdiomaTutor WHERE LOWER(TRIM(idioma)) LIKE LOWER(TRIM(:idioma))";
        $stmtIdioma = $conn->prepare($sqlIdioma);
        $stmtIdioma->bindValue(':idioma', "%$idioma%", PDO::PARAM_STR);
        $stmtIdioma->execute();
        $resultadosIdioma = $stmtIdioma->fetchAll(PDO::FETCH_COLUMN);

        // Se houver IDs de tutores, buscamos os dados correspondentes na tabela Tutores
        if (!empty($resultadosIdioma)) {
            $placeholders = implode(',', array_fill(0, count($resultadosIdioma), '?'));
            $sqlTutores = "SELECT id_tutor, nome, cidade, estado FROM Tutores WHERE id_tutor IN ($placeholders)";
            $stmtTutores = $conn->prepare($sqlTutores);
            $stmtTutores->execute($resultadosIdioma);
            $resultadosTutores = $stmtTutores->fetchAll(PDO::FETCH_ASSOC);
            $resultados = array_merge($resultados, $resultadosTutores);
        }
    }

    // Remover duplicatas do resultado com base no ID do tutor
    $resultados = array_unique($resultados, SORT_REGULAR);

    // Verifica se há resultados
    if ($resultados) {
        $_SESSION['tutores_resultados'] = $resultados;
        header("Location: resultado_tutores.php");
        exit();
    } else {
        $_SESSION['erro_consulta'] = "Não conseguimos encontrar registros, tente novamente.";
        header("Location: resultado_tutores.php");
        exit();
    }

} catch (PDOException $e) {
    die("Erro na consulta: " . $e->getMessage());
}

// Fecha a conexão
$conn = null;
?>
