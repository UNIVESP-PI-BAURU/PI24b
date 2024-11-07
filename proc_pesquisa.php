<?php
// Ativa a exibição de erros
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start(); // Inicia a sessão
require_once 'conexao.php'; // Inclui o arquivo de conexão com o banco de dados

// Verifica se o usuário está logado
if (!isset($_SESSION['id']) || !isset($_SESSION['tipo'])) {
    header("Location: login.php");
    exit();
}

// Coleta os filtros da pesquisa
$cidade = isset($_POST['cidade']) ? trim($_POST['cidade']) : '';
$estado = isset($_POST['estado']) ? trim($_POST['estado']) : '';
$idioma = isset($_POST['idioma']) ? trim($_POST['idioma']) : '';
$tipo_usuario = $_POST['tipo_usuario']; // 'aluno' ou 'tutor'

// Debug: Exibir filtros recebidos
echo "Filtro Cidade: $cidade<br>";  // Debug
echo "Filtro Estado: $estado<br>";  // Debug
echo "Filtro Idioma: $idioma<br>";  // Debug
echo "Tipo de Usuário: $tipo_usuario<br>";  // Debug

// Define a tabela correta de pesquisa, dependendo do tipo de usuário
$tabela_usuario = ($tipo_usuario === 'aluno') ? 'Tutores' : 'Alunos'; // Aluno pesquisa tutores e tutor pesquisa alunos
$campo_oposto = ($tipo_usuario === 'aluno') ? 'idioma' : 'idiomas'; // Ajusta o campo para o idioma de acordo com o tipo de usuário

// Debug: Exibir tabela e campo oposto
echo "Tabela de Pesquisa: $tabela_usuario<br>";  // Debug
echo "Campo de Idioma: $campo_oposto<br>";  // Debug

// Verifica se pelo menos um filtro foi preenchido
if (empty($cidade) && empty($estado) && empty($idioma)) {
    $_SESSION['erro_consulta'] = "É necessário preencher pelo menos um critério de pesquisa.";
    header("Location: resultado_pesquisa.php");
    exit();
}

try {
    // Inicializa a consulta de acordo com os filtros
    $sql = "SELECT id, nome, cidade, estado, idioma
            FROM $tabela_usuario 
            WHERE 1=1"; // A condição WHERE 1=1 facilita a adição dos filtros dinamicamente

    // Debug: Exibir SQL antes de adicionar filtros
    echo "SQL antes de adicionar filtros: $sql<br>";  // Debug

    // Adiciona os filtros à consulta
    if (!empty($idioma)) {
        $sql .= " AND LOWER($campo_oposto) LIKE LOWER(:idioma)";
    }
    if (!empty($cidade)) {
        $sql .= " AND LOWER(cidade) LIKE LOWER(:cidade)";
    }
    if (!empty($estado)) {
        $sql .= " AND LOWER(estado) LIKE LOWER(:estado)";
    }

    // Debug: Exibir SQL com filtros
    echo "SQL com filtros: $sql<br>";  // Debug

    $stmt = $conn->prepare($sql);

    // Vincula os parâmetros dinamicamente
    if (!empty($idioma)) {
        $stmt->bindParam(':idioma', $idioma);
    }
    if (!empty($cidade)) {
        $stmt->bindParam(':cidade', $cidade);
    }
    if (!empty($estado)) {
        $stmt->bindParam(':estado', $estado);
    }

    // Executa a consulta
    $stmt->execute();
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Debug: Exibir resultados da consulta
    echo "Resultados da consulta:<br>";
    print_r($resultados);  // Debug

    // Armazena os resultados na sessão
    $_SESSION['resultados_pesquisa'] = $resultados;

    if (empty($resultados)) {
        $_SESSION['erro_consulta'] = "Nenhum resultado encontrado com os critérios fornecidos.";
    }

    // Redireciona para a página de resultados
    header("Location: resultado_pesquisa.php");
    exit();
} catch (PDOException $e) {
    // Caso ocorra algum erro com a consulta
    $_SESSION['erro_consulta'] = "Erro ao executar a pesquisa: " . $e->getMessage();
    header("Location: resultado_pesquisa.php");
    exit();
}
?>
