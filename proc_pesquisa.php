<?php
session_start(); // Inicia a sessão
require_once '../conexao.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['id']) || !isset($_SESSION['tipo'])) {
    header("Location: ../login.php");
    exit();
}

// Coleta os filtros da pesquisa
$cidade = isset($_POST['cidade']) ? trim($_POST['cidade']) : '';
$estado = isset($_POST['estado']) ? trim($_POST['estado']) : '';
$idioma = isset($_POST['idioma']) ? trim($_POST['idioma']) : '';
$tipo_usuario = $_POST['tipo_usuario']; // 'aluno' ou 'tutor'

// Define a tabela correta de pesquisa, dependendo do tipo de usuário
$tabela_usuario = ($tipo_usuario === 'aluno') ? 'Tutores' : 'Alunos';
$campo_oposto = ($tipo_usuario === 'aluno') ? 'idioma' : 'idiomas';

if (empty($cidade) && empty($estado) && empty($idioma)) {
    $_SESSION['erro_consulta'] = "É necessário preencher pelo menos um critério de pesquisa.";
    header("Location: resultado_pesquisa.php");
    exit();
}

try {
    // Inicializa a consulta de acordo com os filtros
    $sql = "SELECT id, nome, cidade, estado, idioma
            FROM $tabela_usuario 
            WHERE 1=1"; // A condição WHERE 1=1 é para facilitar a adição dos filtros dinamicamente

    if (!empty($idioma)) {
        $sql .= " AND LOWER($campo_oposto) LIKE LOWER(:idioma)";
    }
    if (!empty($cidade)) {
        $sql .= " AND LOWER(cidade) LIKE LOWER(:cidade)";
    }
    if (!empty($estado)) {
        $sql .= " AND LOWER(estado) LIKE LOWER(:estado)";
    }

    $stmt = $conn->prepare($sql);

    // Bind dos valores
    if (!empty($idioma)) {
        $stmt->bindValue(':idioma', "%$idioma%", PDO::PARAM_STR);
    }
    if (!empty($cidade)) {
        $stmt->bindValue(':cidade', "%$cidade%", PDO::PARAM_STR);
    }
    if (!empty($estado)) {
        $stmt->bindValue(':estado', "%$estado%", PDO::PARAM_STR);
    }

    $stmt->execute();
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    $_SESSION['erro_consulta'] = "Erro ao realizar a consulta.";
    header("Location: resultado_pesquisa.php");
    exit();
}

// Armazena resultados na sessão para exibição
$_SESSION['resultados_pesquisa'] = $resultados;

// Redireciona para a página de resultados
header("Location: resultado_pesquisa.php");
exit();
?>
