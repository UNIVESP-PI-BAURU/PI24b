<?php
// Definindo o cabeçalho para o retorno em JSON
header('Content-Type: application/json');

// Carregando o arquivo JSON com os idiomas
$idiomas = json_decode(file_get_contents('idioma.json'), true);

// Verificando se há uma consulta
if (isset($_GET['q'])) {
    $query = strtolower($_GET['q']);
    $resultados = [];

    // Filtrando idiomas que contêm a consulta
    foreach ($idiomas as $idioma) {
        if (strpos(strtolower($idioma['idioma']), $query) !== false) {
            $resultados[] = $idioma['idioma'];
        }
    }

    // Retornando os resultados em formato JSON
    echo json_encode($resultados);
}
?>
