<?php
session_start();
require_once '../conexao.php';

// Define o tipo de busca
$tipo = $_GET['tipo'] ?? '';
$term = $_GET['term'] ?? '';

// Inicializa um array para armazenar os resultados
$resultados = [];

if ($tipo === 'cidade') {
    $sql = "SELECT DISTINCT cidade FROM Tutores WHERE cidade LIKE :term";
} elseif ($tipo === 'estado') {
    $sql = "SELECT DISTINCT estado FROM Tutores WHERE estado LIKE :term";
} elseif ($tipo === 'idioma') {
    $sql = "SELECT DISTINCT idioma FROM IdiomaTutor WHERE idioma LIKE :term";
} else {
    echo json_encode([]);
    exit();
}

$stmt = $conn->prepare($sql);
$stmt->bindValue(':term', "%$term%", PDO::PARAM_STR);
$stmt->execute();

if ($tipo === 'cidade') {
    $resultados = $stmt->fetchAll(PDO::FETCH_COLUMN);
} elseif ($tipo === 'estado') {
    $resultados = $stmt->fetchAll(PDO::FETCH_COLUMN);
} elseif ($tipo === 'idioma') {
    $resultados = $stmt->fetchAll(PDO::FETCH_COLUMN);
}

echo json_encode($resultados);
?>
