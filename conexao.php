<?php
// Função para carregar variáveis do arquivo .env
function loadEnv($filePath) {
    if (file_exists($filePath)) {
        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            putenv(trim($line));
        }
    } else {
        die("Erro: Arquivo .env não encontrado em $filePath");
    }
}

// Carregue as variáveis de ambiente
loadEnv(__DIR__ . '/.env');

// Conexão com o banco de dados usando as variáveis de ambiente
$host = getenv('DB_HOST');
$dbname = getenv('DB_NAME');
$user = getenv('DB_USER');
$password = getenv('DB_PASSWORD');

// Verifique se todas as variáveis de ambiente foram carregadas
if (!$host || !$dbname || !$user || !$password) {
    die("Erro: Variáveis de ambiente para conexão com o banco de dados não foram corretamente carregadas.");
}

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}
?>
