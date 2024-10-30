<?php
require_once '../conexao.php'; 

// Recupera o termo de busca
$termo = isset($_GET['term']) ? trim($_GET['term']) : '';
$tipo = isset($_GET['tipo']) ? trim($_GET['tipo']) : ''; // Pode ser 'cidade', 'estado' ou 'idioma'

$resultados = [];

try {
    // Prepara a consulta dependendo do tipo
    if ($tipo === 'cidade') {
        $sql = "SELECT DISTINCT cidade FROM Tutores WHERE LOWER(cidade) LIKE LOWER(:termo)";
    } elseif ($tipo === 'estado') {
        $sql = "SELECT DISTINCT estado FROM Tutores WHERE LOWER(estado) LIKE LOWER(:termo)";
    } elseif ($tipo === 'idioma') {
        $sql = "SELECT DISTINCT idioma FROM IdiomaTutor WHERE LOWER(idioma) LIKE LOWER(:termo)";
    } else {
        echo json_encode($resultados); // Tipo inválido
        exit();
    }

    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':termo', "%$termo%", PDO::PARAM_STR);
    $stmt->execute();

    if ($tipo === 'idioma') {
        $resultados = $stmt->fetchAll(PDO::FETCH_COLUMN);
    } else {
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // Formata resultados para apenas incluir os valores
        $resultados = array_column($resultados, $tipo); // 'cidade' ou 'estado'
    }

    // Retorna os resultados como JSON
    echo json_encode($resultados);
} catch (PDOException $e) {
    // Tratamento de erro
    echo json_encode([]);
}
?>
