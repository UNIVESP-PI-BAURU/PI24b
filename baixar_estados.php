<?php
// URL da API do IBGE para listar todos os estados
$url = "https://servicodados.ibge.gov.br/api/v1/localidades/estados";

// Caminho e nome do arquivo onde o JSON será salvo
$arquivo = __DIR__ . "/estados.json";

try {
    // Busca o conteúdo da URL
    $json = file_get_contents($url);

    // Verifica se houve erro na requisição
    if ($json === false) {
        throw new Exception("Erro ao acessar a API do IBGE.");
    }

    // Salva o JSON no arquivo
    file_put_contents($arquivo, $json);

    echo "JSON de estados salvo com sucesso em: {$arquivo}";
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage();
}
?>
