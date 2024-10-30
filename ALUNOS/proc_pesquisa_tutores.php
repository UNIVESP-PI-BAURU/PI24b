<?php
session_start();

require_once '../conexao.php'; 

// Coleta do filtro de idioma
$idioma = isset($_POST['idioma']) ? trim($_POST['idioma']) : '';

// Verifica se o filtro de idioma foi preenchido
if (empty($idioma)) {
    $_SESSION['erro_consulta'] = "É necessário preencher o critério de idioma.";
    header("Location: resultado_tutores.php");
    exit();
}

try {
    // Inicializa um array para armazenar resultados
    $resultados = [];

    // Consulta para obter tutores com o idioma específico
    $sql = "SELECT t.id AS id_tutor, t.nome, t.cidade, t.estado
            FROM Tutores t
            INNER JOIN IdiomaTutor it ON t.id = it.id_tutor
            WHERE LOWER(TRIM(it.idioma)) LIKE LOWER(TRIM(:idioma))";

    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':idioma', "%$idioma%", PDO::PARAM_STR);
    $stmt->execute();

    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Debug: verifique os resultados obtidos
    var_dump($resultados); // Adicionei isso para verificar os resultados
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
