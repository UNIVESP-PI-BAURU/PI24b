<?php
session_start();

require_once '../conexao.php'; 

// Coleta do filtro de idioma
$idioma = isset($_POST['idioma']) ? trim($_POST['idioma']) : '';

// Verifica se o filtro de idioma foi preenchido
if (empty($idioma)) {
    $_SESSION['erro_consulta'] = "É necessário preencher o critério de idioma.";
    header("Location: resultado_alunos.php");
    exit();
}

try {
    // Inicializa um array para armazenar resultados
    $resultados = [];

    // Consulta para obter alunos com o idioma específico
    $sql = "SELECT a.id, a.nome, a.cidade, a.estado 
            FROM Alunos a
            INNER JOIN IdiomaAlunos ia ON a.id = ia.id_aluno
            WHERE LOWER(TRIM(ia.idioma)) LIKE LOWER(TRIM(:idioma))";

    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':idioma', "%$idioma%", PDO::PARAM_STR);
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
    die("Erro na consulta: " . $e->getMessage());
}

// Fecha a conexão
$conn = null;
?>
