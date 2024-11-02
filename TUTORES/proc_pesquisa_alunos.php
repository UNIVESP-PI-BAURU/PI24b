<?php
session_start();
require_once '../conexao.php';

// Coleta dos filtros
$cidade = isset($_POST['cidade']) ? trim($_POST['cidade']) : '';
$estado = isset($_POST['estado']) ? trim($_POST['estado']) : '';
$idioma = isset($_POST['idioma']) ? trim($_POST['idioma']) : '';
$id_tutor = isset($_POST['id_tutor']) ? trim($_POST['id_tutor']) : ''; // Captura a ID do tutor

// Verifica se pelo menos um dos filtros foi preenchido
if (empty($cidade) && empty($estado) && empty($idioma)) {
    $_SESSION['erro_consulta'] = "É necessário preencher pelo menos um critério de pesquisa.";
    header("Location: resultado_alunos.php");
    exit();
}

try {
    // Inicializa um array para armazenar resultados
    $resultados = [];

    // Construir a consulta dependendo dos filtros preenchidos
    $sql = "SELECT a.id AS id_aluno, a.nome, a.cidade, a.estado, GROUP_CONCAT(ia.idioma SEPARATOR ', ') AS idiomas
            FROM Alunos a
            INNER JOIN IdiomaAluno ia ON a.id = ia.id_aluno
            WHERE 1=1"; // Para facilitar a adição de condições

    if (!empty($idioma)) {
        $sql .= " AND LOWER(TRIM(ia.idioma)) LIKE LOWER(TRIM(:idioma))";
    }
    if (!empty($cidade)) {
        $sql .= " AND LOWER(TRIM(a.cidade)) LIKE LOWER(TRIM(:cidade))";
    }
    if (!empty($estado)) {
        $sql .= " AND LOWER(TRIM(a.estado)) LIKE LOWER(TRIM(:estado))";
    }

    $sql .= " GROUP BY a.id"; // Agrupa os resultados para evitar duplicação

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

    // Verifica se há resultados
    if (!empty($resultados)) {
        $_SESSION['alunos_resultados'] = $resultados; // Armazena os resultados na sessão
        $_SESSION['id_tutor'] = $id_tutor; // Armazena a ID do tutor na sessão (se necessário)
        header("Location: resultado_alunos.php"); // Redireciona para a página de resultados
        exit();
    } else {
        $_SESSION['erro_consulta'] = "Não conseguimos encontrar registros, tente novamente.";
        header("Location: resultado_alunos.php"); // Redireciona para a página de resultados
        exit();
    }

} catch (PDOException $e) {
    $_SESSION['erro_consulta'] = "Ocorreu um erro ao processar sua solicitação. Tente novamente mais tarde.";
    header("Location: resultado_alunos.php"); // Redireciona para a página de resultados
    exit();
}

// Fecha a conexão
$conn = null;
?>
