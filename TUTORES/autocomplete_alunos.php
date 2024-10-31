<?php
require_once '../conexao.php'; 

// Recupera o termo de busca e tipo
$termo = isset($_GET['term']) ? trim($_GET['term']) : '';
$tipo = isset($_GET['tipo']) ? trim($_GET['tipo']) : ''; 

$resultados = [];

// Debug: imprime o termo e tipo recebidos
error_log("Termo: $termo, Tipo: $tipo");

try {
    // Verifica se o termo e o tipo são válidos
    if (empty($termo) || !in_array($tipo, ['cidade', 'estado', 'idioma'])) {
        echo json_encode($resultados); // Tipo inválido ou termo vazio
        exit();
    }

    // Prepara a consulta dependendo do tipo
    if ($tipo === 'cidade') {
        $sql = "SELECT DISTINCT cidade FROM Alunos WHERE LOWER(TRIM(cidade)) LIKE LOWER(TRIM(:termo))";
    } elseif ($tipo === 'estado') {
        $sql = "SELECT DISTINCT estado FROM Alunos WHERE LOWER(TRIM(estado)) LIKE LOWER(TRIM(:termo))";
    } elseif ($tipo === 'idioma') {
        $stmt = $pdo->prepare("SELECT DISTINCT idioma FROM IdiomaAluno WHERE LOWER(TRIM(idioma)) LIKE LOWER(TRIM(:termo))");

    }

    // Debug: imprime a consulta SQL
    error_log("SQL: $sql");

    // Executa a consulta
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':termo', "%$termo%", PDO::PARAM_STR);
    $stmt->execute();

    // Manipula os resultados
    if ($tipo === 'idioma') {
   
        $stmt = $pdo->prepare("SELECT DISTINCT idioma FROM IdiomaAluno WHERE LOWER(TRIM(idioma)) LIKE LOWER(TRIM(:termo))");
        } elseif ($tipo === 'estado') {
            $stmt = $pdo->prepare("SELECT DISTINCT estado FROM Alunos WHERE LOWER(TRIM(estado)) LIKE LOWER(TRIM(:termo))");
        }

    // Retorna os resultados como JSON
    echo json_encode($resultados);
} catch (PDOException $e) {
    error_log("Erro na execução: " . $e->getMessage());
    echo json_encode([]);
}
?>
