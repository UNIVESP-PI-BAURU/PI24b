<?php
require_once '../conexao.php'; 

// Recupera o termo de busca e tipo
$termo = isset($_GET['term']) ? trim($_GET['term']) : '';
$tipo = isset($_GET['tipo']) ? trim($_GET['tipo']) : ''; // Pode ser 'cidade', 'estado' ou 'idioma'

$resultados = [];

try {
    // Verifica se o termo e o tipo são válidos
    if (empty($termo) || !in_array($tipo, ['cidade', 'estado', 'idioma'])) {
        echo json_encode($resultados); // Tipo inválido ou termo vazio
        exit();
    }

    // Prepara a consulta dependendo do tipo
    if ($tipo === 'cidade') {
        $sql = "SELECT DISTINCT cidade FROM Tutores WHERE LOWER(TRIM(cidade)) LIKE LOWER(TRIM(:termo))";
    } elseif ($tipo === 'estado') {
        $sql = "SELECT DISTINCT estado FROM Tutores WHERE LOWER(TRIM(estado)) LIKE LOWER(TRIM(:termo))";
    } elseif ($tipo === 'idioma') {
        $sql = "SELECT DISTINCT idioma FROM IdiomaTutor WHERE LOWER(TRIM(idioma)) LIKE LOWER(TRIM(:termo))";
    }

    // Executa a consulta
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':termo', "%$termo%", PDO::PARAM_STR);
    $stmt->execute();

    // Manipula os resultados
    if ($tipo === 'idioma') {
        $resultados = $stmt->fetchAll(PDO::FETCH_COLUMN); // Retorna uma lista simples de idiomas
    } else {
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $resultados = array_column($resultados, $tipo); // Extrai os valores de cidade ou estado
    }

    // Retorna os resultados como JSON
    echo json_encode($resultados);
} catch (PDOException $e) {
    // Tratamento de erro
    error_log("Erro na consulta: " . $e->getMessage()); // Loga o erro para depuração
    echo json_encode([]); // Retorna uma resposta vazia em caso de erro
}
?>
