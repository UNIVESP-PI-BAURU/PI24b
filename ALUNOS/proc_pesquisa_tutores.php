<?php
session_start();

// Verifica se o usuário está logado e redireciona para login se não estiver
if (!isset($_SESSION['id_aluno']) && !isset($_SESSION['id_tutor'])) {
    header("Location: ../login.php");
    exit();
}

require_once '../conexao.php'; // Inclui a conexão com o banco

// Define o tipo de usuário e busca os dados
$tipo_usuario = isset($_SESSION['id_aluno']) ? 'aluno' : 'tutor';
$id_usuario = $_SESSION['id_' . $tipo_usuario];

// Coletar os dados do formulário
$cidade = isset($_POST['cidade']) ? trim($_POST['cidade']) : '';
$estado = isset($_POST['estado']) ? trim($_POST['estado']) : '';
$idioma = isset($_POST['idioma']) ? trim($_POST['idioma']) : '';

// Criar a consulta
$sql = "SELECT t.nome, t.cidade, t.estado, it.idioma 
        FROM Tutores t
        JOIN IdiomaTutor it ON t.id_tutor = it.id_tutor WHERE 1=1";

// Adicionar condições baseadas nos filtros fornecidos
if (!empty($cidade)) {
    $sql .= " AND t.cidade LIKE '%" . $conn->real_escape_string($cidade) . "%'";
}
if (!empty($estado)) {
    $sql .= " AND t.estado LIKE '%" . $conn->real_escape_string($estado) . "%'";
}
if (!empty($idioma)) {
    $sql .= " AND it.idioma LIKE '%" . $conn->real_escape_string($idioma) . "%'";
}

// Executar a consulta
$result = $conn->query($sql);

// Verificar e exibir os resultados
if ($result) {
    if ($result->num_rows > 0) {
        echo "<h3>Resultados da Pesquisa:</h3>";
        echo "<ul>";
        while ($row = $result->fetch_assoc()) {
            echo "<li>" . htmlspecialchars($row['nome']) . " - " . htmlspecialchars($row['cidade']) . ", " . htmlspecialchars($row['estado']) . " (" . htmlspecialchars($row['idioma']) . ")</li>";
        }
        echo "</ul>";
    } else {
        // Mensagem quando não há resultados
        echo "<p>Desculpe, não localizamos registros com estes dados. Favor tentar novamente.</p>";
    }
} else {
    // Mensagem de erro na execução da consulta
    echo "<p>Ocorreu um erro ao executar a consulta. Tente novamente mais tarde.</p>";
}

// Fechar a conexão
$conn->close();
?>
