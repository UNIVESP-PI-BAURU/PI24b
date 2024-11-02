<?php
session_start();
require_once '../conexao.php';

// Define o tipo de busca
$tipo = $_GET['tipo'] ?? '';
$term = $_GET['term'] ?? '';

// Inicializa um array para armazenar os resultados
$resultados = [];

if (empty($term) || !in_array($tipo, ['cidade', 'estado', 'idioma'])) {
    echo json_encode([]); // Termo vazio ou tipo inválido
    exit();
}

try {
    if ($tipo === 'cidade') {
        $sql = "SELECT DISTINCT cidade FROM Tutores WHERE LOWER(TRIM(cidade)) LIKE LOWER(TRIM(:term))";
    } elseif ($tipo === 'estado') {
        $sql = "SELECT DISTINCT estado FROM Tutores WHERE LOWER(TRIM(estado)) LIKE LOWER(TRIM(:term))";
    } elseif ($tipo === 'idioma') {
        $sql = "SELECT DISTINCT idioma FROM IdiomaTutor WHERE LOWER(TRIM(idioma)) LIKE LOWER(TRIM(:term))";
    } else {
        echo json_encode([]);
        exit();
    }

    // Prepara e executa a consulta
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':term', "%$term%", PDO::PARAM_STR);
    $stmt->execute();

    // Armazena os resultados
    $resultados = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // Retorna os resultados como JSON
    echo json_encode($resultados);
} catch (PDOException $e) {
    error_log("Erro na execução: " . $e->getMessage());
    echo json_encode([]);
}
?>
