<?php
session_start();

require_once '../conexao.php'; 

// Coleta dos filtros
$cidade = isset($_POST['cidade']) ? trim($_POST['cidade']) : '';
$estado = isset($_POST['estado']) ? trim($_POST['estado']) : '';
$idioma = isset($_POST['idioma']) ? trim($_POST['idioma']) : '';

try {
    // Inicializa um array para armazenar resultados
    $resultados = [];

    // Consulta para cidade
    if (!empty($cidade)) {
        $sqlCidade = "SELECT id, nome, cidade, estado FROM Alunos WHERE LOWER(TRIM(cidade)) LIKE LOWER(TRIM(:cidade))";
        $stmtCidade = $conn->prepare($sqlCidade);
        $stmtCidade->bindValue(':cidade', "%$cidade%", PDO::PARAM_STR);
        $stmtCidade->execute();
        $resultadosCidade = $stmtCidade->fetchAll(PDO::FETCH_ASSOC);
        $resultados = array_merge($resultados, $resultadosCidade);
    }

    // Consulta para estado
    if (!empty($estado)) {
        $sqlEstado = "SELECT id, nome, cidade, estado FROM Alunos WHERE LOWER(TRIM(estado)) LIKE LOWER(TRIM(:estado))";
        $stmtEstado = $conn->prepare($sqlEstado);
        $stmtEstado->bindValue(':estado', "%$estado%", PDO::PARAM_STR);
        $stmtEstado->execute();
        $resultadosEstado = $stmtEstado->fetchAll(PDO::FETCH_ASSOC);
        $resultados = array_merge($resultados, $resultadosEstado);
    }

    // Consulta para idioma (de forma diferente)
    if (!empty($idioma)) {
        // Obtemos os IDs dos alunos que possuem o idioma específico
        $sqlIdioma = "SELECT id_aluno FROM IdiomaAlunos WHERE LOWER(TRIM(idioma)) LIKE LOWER(TRIM(:idioma))";
        $stmtIdioma = $conn->prepare($sqlIdioma);
        $stmtIdioma->bindValue(':idioma', "%$idioma%", PDO::PARAM_STR);
        $stmtIdioma->execute();
        $resultadosIdioma = $stmtIdioma->fetchAll(PDO::FETCH_COLUMN);

        // Se houver IDs de alunos, buscamos os dados correspondentes na tabela Alunos
        if (!empty($resultadosIdioma)) {
            $placeholders = implode(',', array_fill(0, count($resultadosIdioma), '?'));
            $sqlAlunos = "SELECT id, nome, cidade, estado FROM Alunos WHERE id IN ($placeholders)";
            $stmtAlunos = $conn->prepare($sqlAlunos);
            $stmtAlunos->execute($resultadosIdioma);
            $resultadosAlunos = $stmtAlunos->fetchAll(PDO::FETCH_ASSOC);
            $resultados = array_merge($resultados, $resultadosAlunos);
        }
    }

    // Remover duplicatas do resultado com base no ID do aluno
    $resultados = array_unique($resultados, SORT_REGULAR);

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
