<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['id_usuario']) || !isset($_SESSION['tipo_usuario'])) {
    header("Location: login.php");
    exit();
}

require_once 'conexao.php'; // Inclui a conexão com o banco de dados

// Verifica a conexão com o banco de dados
if (!$conn) {
    die("Falha na conexão com o banco de dados.");
}

$id_usuario = $_SESSION['id_usuario'];
$tipo_usuario = $_SESSION['tipo_usuario'];

// Consultas dependendo do tipo de usuário

// Aulas Agendadas
$aulas_agendadas = [];
if ($tipo_usuario === 'aluno') {
    $sql_aulas = "SELECT A.id, A.data_hora, T.nome AS tutor_nome FROM Aulas A JOIN Tutores T ON A.id_tutor = T.id WHERE A.id_aluno = :id_aluno ORDER BY A.data_hora DESC";
    $stmt_aulas = $conn->prepare($sql_aulas);
    $stmt_aulas->bindParam(':id_aluno', $id_usuario, PDO::PARAM_INT);
    $stmt_aulas->execute();
    $aulas_agendadas = $stmt_aulas->fetchAll(PDO::FETCH_ASSOC);
} elseif ($tipo_usuario === 'tutor') {
    $sql_aulas = "SELECT A.id, A.data_hora, U.nome AS aluno_nome FROM Aulas A JOIN Alunos U ON A.id_aluno = U.id WHERE A.id_tutor = :id_tutor ORDER BY A.data_hora DESC";
    $stmt_aulas = $conn->prepare($sql_aulas);
    $stmt_aulas->bindParam(':id_tutor', $id_usuario, PDO::PARAM_INT);
    $stmt_aulas->execute();
    $aulas_agendadas = $stmt_aulas->fetchAll(PDO::FETCH_ASSOC);
}

// Curtidas (Somente para tutores)
$curtidas = [];
if ($tipo_usuario === 'tutor') {
    $sql_curtidas = "SELECT A.nome, A.foto_perfil FROM Curtidas C JOIN Alunos A ON C.id_aluno = A.id WHERE C.id_tutor = :id_tutor";
    $stmt_curtidas = $conn->prepare($sql_curtidas);
    $stmt_curtidas->bindParam(':id_tutor', $id_usuario, PDO::PARAM_INT);
    $stmt_curtidas->execute();
    $curtidas = $stmt_curtidas->fetchAll(PDO::FETCH_ASSOC);
}

// Últimas mensagens (dependendo do tipo de usuário)
$ultimas_mensagens = [];
if ($tipo_usuario === 'aluno') {
    $sql_mensagens = "SELECT C.id, C.mensagem, C.data_envio, T.nome AS tutor_nome FROM Mensagens C JOIN Tutores T ON C.id_tutor = T.id WHERE C.id_aluno = :id_aluno ORDER BY C.data_envio DESC LIMIT 5";
    $stmt_mensagens = $conn->prepare($sql_mensagens);
    $stmt_mensagens->bindParam(':id_aluno', $id_usuario, PDO::PARAM_INT);
    $stmt_mensagens->execute();
    $ultimas_mensagens = $stmt_mensagens->fetchAll(PDO::FETCH_ASSOC);
} elseif ($tipo_usuario === 'tutor') {
    $sql_mensagens = "SELECT C.id, C.mensagem, C.data_envio, U.nome AS aluno_nome FROM Mensagens C JOIN Alunos U ON C.id_aluno = U.id WHERE C.id_tutor = :id_tutor ORDER BY C.data_envio DESC LIMIT 5";
    $stmt_mensagens = $conn->prepare($sql_mensagens);
    $stmt_mensagens->bindParam(':id_tutor', $id_usuario, PDO::PARAM_INT);
    $stmt_mensagens->execute();
    $ultimas_mensagens = $stmt_mensagens->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - <?php echo htmlspecialchars($_SESSION['nome']); ?></title>
    <link rel="stylesheet" href="ASSETS/CSS/style.css">
</head>
<body>

    <header class="header">
        <img src="ASSETS/IMG/capa.png" alt="Capa do site">
    </header>

    <nav class="navbar">
        <button onclick="window.location.href='index.php'">Home</button>
        <button onclick="window.location.href='dashboard.php'">Dashboard</button>
        <button onclick="window.location.href='logout.php'">Logout</button>
    </nav>

    <main class="main-content">
        <section class="search-section">
            <!-- Aqui vai o seu código de pesquisa já existente -->
        </section>

        <!-- Complemento: Aulas Agendadas -->
        <section class="aulas-section">
            <h3>Aulas Agendadas</h3>
            <ul>
                <?php foreach ($aulas_agendadas as $aula): ?>
                    <li>
                        <p><strong><?php echo ($tipo_usuario === 'aluno') ? 'Tutor' : 'Aluno'; ?>:</strong> <?php echo ($tipo_usuario === 'aluno') ? htmlspecialchars($aula['tutor_nome']) : htmlspecialchars($aula['aluno_nome']); ?></p>
                        <p><strong>Data e Hora:</strong> <?php echo htmlspecialchars($aula['data_hora']); ?></p>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>

        <!-- Complemento: Curtidas (Somente para tutores) -->
        <?php if ($tipo_usuario === 'tutor'): ?>
            <section class="curtidas-section">
                <h3>Suas Curtidas</h3>
                <ul>
                    <?php foreach ($curtidas as $curtida): ?>
                        <li>
                            <img src="<?php echo htmlspecialchars($curtida['foto_perfil']); ?>" alt="Foto de Perfil" class="avatar">
                            <p><?php echo htmlspecialchars($curtida['nome']); ?></p>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </section>
        <?php endif; ?>

        <!-- Complemento: Últimas Mensagens -->
        <section class="mensagens-section">
            <h3>Últimas Mensagens</h3>
            <ul>
                <?php foreach ($ultimas_mensagens as $mensagem): ?>
                    <li>
                        <p><strong><?php echo ($tipo_usuario === 'aluno') ? 'Tutor' : 'Aluno'; ?>:</strong> <?php echo ($tipo_usuario === 'aluno') ? htmlspecialchars($mensagem['tutor_nome']) : htmlspecialchars($mensagem['aluno_nome']); ?></p>
                        <p><strong>Mensagem:</strong> <?php echo htmlspecialchars($mensagem['mensagem']); ?></p>
                        <p><small><?php echo htmlspecialchars($mensagem['data_envio']); ?></small></p>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>

    </main>

    <footer class="footer">
        UNIVESP PI 2024
    </footer>
    
</body>
</html>
