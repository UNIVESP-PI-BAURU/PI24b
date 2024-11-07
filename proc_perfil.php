<?php
// Inicia a sessão e verifica login
session_start();

// Debug: Verificando se as variáveis da sessão estão definidas
echo "ID do usuário: " . (isset($_SESSION['id']) ? $_SESSION['id'] : 'Não definido') . "<br>";  // Debug
echo "Tipo do usuário: " . (isset($_SESSION['tipo']) ? $_SESSION['tipo'] : 'Não definido') . "<br>";  // Debug

if (!isset($_SESSION['id']) || !isset($_SESSION['tipo'])) {
    header("Location: login.php");
    exit();
}

// Inclui a conexão com o banco
require_once 'conexao.php';

// Determina o tipo de usuário e busca os dados
$tipo_usuario = $_SESSION['tipo']; // 'aluno' ou 'tutor'
$id_usuario = $_SESSION['id']; // ID comum para todos os tipos
$tabela_usuario = ($tipo_usuario === 'aluno') ? 'Alunos' : 'Tutores';

// Debug: Exibindo informações sobre o tipo de usuário e tabela
echo "Tipo de usuário: $tipo_usuario<br>";  // Debug
echo "Tabela associada ao usuário: $tabela_usuario<br>";  // Debug

try {
    // Consulta SQL para buscar dados do usuário
    $sql = "SELECT nome, email, foto_perfil, cidade, estado, data_nascimento, biografia, idioma
            FROM $tabela_usuario WHERE id = :id";
    $stmt = $conn->prepare($sql);
    
    // Debug: Verificando os parâmetros antes de bind
    echo "Consulta SQL: $sql<br>";  // Debug
    echo "ID do usuário para consulta: $id_usuario<br>";  // Debug
    
    // Vincula o parâmetro de ID
    $stmt->bindParam(':id', $id_usuario, PDO::PARAM_INT);

    // Executa a consulta
    $stmt->execute();
    
    // Verifica se há erros na execução
    if ($stmt->errorCode() != '00000') {
        // Log de erro se houver problemas na execução
        error_log("Erro ao executar consulta: " . implode(", ", $stmt->errorInfo()));
    }

    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verifica se encontrou o usuário
    if (!$usuario) {
        die("Usuário não encontrado.");
    }

    // Debug: Exibindo os dados do usuário retornados
    echo "Dados do usuário: <pre>" . print_r($usuario, true) . "</pre>";  // Debug

    // Extrai o idioma
    $idioma = !empty($usuario['idioma']) ? $usuario['idioma'] : 'Não informado';

    // Debug: Exibindo o idioma
    echo "Idioma do usuário: $idioma<br>";  // Debug

} catch (PDOException $e) {
    error_log("Erro ao carregar perfil: " . $e->getMessage());
    echo "Erro ao carregar perfil: " . $e->getMessage();
    exit();
}
?>
