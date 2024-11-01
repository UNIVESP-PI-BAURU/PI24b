<?php
header('Content-Type: application/json');

// Obtém a consulta do parâmetro da URL
$q = isset($_GET['q']) ? $_GET['q'] : '';

// Carrega os idiomas do arquivo JSON
$idiomas = json_decode(file_get_contents('idioma.json'), true);

// Filtra os idiomas com base na consulta
$resultados = [];
foreach ($idiomas as $idioma) {
    if (stripos($idioma['idioma'], $q) === 0) { // Verifica se o idioma começa com o que foi digitado
        $resultados[] = $idioma['idioma'];
    }
}

// Retorna os resultados em formato JSON
echo json_encode($resultados);
?>
