<?php
// Lê o arquivo JSON
$jsonData = file('idioma.json');

// Cria um array para armazenar os dados
$languages = [];

// Percorre cada linha do arquivo
foreach ($jsonData as $line) {
    // Remove espaços em branco e decodifica a linha JSON
    $decoded = json_decode(trim($line), true);
    if ($decoded) {
        // Adiciona cada par chave-valor ao array
        $languages = array_merge($languages, $decoded);
    }
}

// Converte o array para JSON
$jsonOutput = json_encode($languages, JSON_PRETTY_PRINT);

// Salva o novo JSON em um arquivo
file_put_contents('idioma_corrigido.json', $jsonOutput);
echo "Arquivo corrigido e salvo como 'idioma_corrigido.json'.";
?>
