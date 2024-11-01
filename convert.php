<?php
// Caminho do arquivo JSON original
$jsonFilePath = 'language.json';

// Lê o conteúdo do arquivo JSON
$jsonData = file_get_contents($jsonFilePath);

// Decodifica o JSON em um array associativo
$arrayData = json_decode($jsonData, true);

// Verifica se a decodificação foi bem-sucedida
if ($arrayData === null) {
    die("Erro ao decodificar JSON.");
}

// Cria ou abre um novo arquivo para gravação
$outputFilePath = 'idioma.json';
$outputFile = fopen($outputFilePath, 'w');

// Escreve cada idioma em uma linha
foreach ($arrayData as $code => $language) {
    fwrite($outputFile, json_encode([$code => $language]) . PHP_EOL);
}

// Fecha o arquivo
fclose($outputFile);

echo "Conversão concluída. O arquivo formatado foi salvo como '$outputFilePath'.";
?>
