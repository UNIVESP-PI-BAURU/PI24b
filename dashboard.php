<?php
session_start(); // Inicia a sessão

// Verifica se o usuário está logado e redireciona para login se não estiver
if (!isset($_SESSION['id_usuario']) || !isset($_SESSION['tipo_usuario'])) {
    header("Location: login.php"); // Redireciona para login
    exit(); // Evita que o código continue
}

require_once 'conexao.php'; // Inclui a conexão com o banco

// Verifica a conexão com o banco de dados
if (!$conn) {
    die("Falha na conexão com o banco de dados.");
}

// Define o tipo de usuário e busca os dados
$tipo_usuario = $_SESSION['tipo_usuario']; // 'aluno' ou 'tutor'
$id_usuario = $_SESSION['id_usuario']; // ID do usuário, seja aluno ou tutor

// Exibindo dados de depuração
echo "<!-- Debugging - Tipo de Usuário e ID do Usuário -->";
var_dump($tipo_usuario, $id_usuario); // Verificando as variáveis de sessão

// Consulta as últimas 5 mensagens enviadas para o usuário logado
$sql_mensagens = "
    SELECT M.mensagem, M.data_envio, U.nome AS remetente_nome
    FROM Mensagens M
    JOIN Alunos A ON M.id_remetente = A.id
    LEFT JOIN Tutores T ON M.id_remetente = T.id
    LEFT JOIN Alunos U ON M.id_destinatario = U.id
    LEFT JOIN Tutores U_Tutor ON M.id_destinatario = U_Tutor.id
    WHERE M.id_destinatario = :id_usuario
    ORDER BY M.data_envio DESC
    LIMIT 5
";
$stmt_mensagens = $conn->prepare($sql_mensagens);
$stmt_mensagens->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
$stmt_mensagens->execute();
$mensagens = $stmt_mensagens->fetchAll(PDO::FETCH_ASSOC);

// Exibindo dados de depuração das mensagens
echo "<!-- Debugging - Mensagens Recebidas -->";
var_dump($mensagens); // Verificando as mensagens retornadas

// Consulta os dados do usuário (mesma lógica usada antes)
$tabela_usuario = ($tipo_usuario === 'aluno') ? 'Alunos' : 'Tutores';
$sql_usuario = "SELECT nome, foto_perfil, cidade, estado FROM $tabela_usuario WHERE id = :id";
$stmt_usuario = $conn->prepare($sql_usuario);
$stmt_usuario->bindParam(':id', $id_usuario);
$stmt_usuario->execute();
$usuario = $stmt_usuario->fetch(PDO::FETCH_ASSOC);

// Exibindo dados do usuário para depuração
echo "<!-- Debugging - Dados do Usuário Encontrado -->";
var_dump($usuario);  // Verificando os dados retornados

// Se o usuário não for encontrado, redireciona para o login
if (!$usuario) {
    header("Location: login.php");
    exit();
}

// Se o usuário for tutor, consulta a quantidade de curtidas recebidas
if ($tipo_usuario === 'tutor') {
    $sql_curtidas = "
        SELECT COUNT(*) AS total_curtidas 
        FROM Curtidas 
        WHERE id_tutor = :id_usuario
    ";
    $stmt_curtidas = $conn->prepare($sql_curtidas);
    $stmt_curtidas->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $stmt_curtidas->execute();
    $curtidas = $stmt_curtidas->fetch(PDO::FETCH_ASSOC);
    $total_curtidas = $curtidas['total_curtidas'];
} else {
    $total_curtidas = 0;
}

// Defina a variável para aulas agendadas como array vazio por padrão
$aulas_agendadas = [];

// Consulta as aulas agendadas para o usuário
$sql_aulas = "
    SELECT A.id, A.data_hora, 
           U.nome AS aluno_nome, 
           T.nome AS tutor_nome 
    FROM Aulas A
    LEFT JOIN Alunos U ON A.id_aluno = U.id
    LEFT JOIN Tutores T ON A.id_tutor = T.id
    WHERE A.id_aluno = :id_usuario OR A.id_tutor = :id_usuario
    ORDER BY A.data_hora DESC
";
$stmt_aulas = $conn->prepare($sql_aulas);
$stmt_aulas->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
$stmt_aulas->execute();
$aulas_agendadas = $stmt_aulas->fetchAll(PDO::FETCH_ASSOC);

// Pesquisa de Alunos ou Tutores
if (isset($_GET['buscar'])) {
    $termo_busca = trim($_GET['termo']); // Obtém o termo de pesquisa
    $cidade = isset($_GET['cidade']) ? $_GET['cidade'] : '';
    $estado = isset($_GET['estado']) ? $_GET['estado'] : '';
    
    // Filtragem baseada nos campos preenchidos
    if ($tipo_usuario === 'aluno') {
        // Pesquisa tutores
        $sql_pesquisa = "SELECT id, nome, foto_perfil, cidade, estado FROM Tutores WHERE nome LIKE :termo";
        if ($cidade) {
            $sql_pesquisa .= " AND cidade LIKE :cidade";
        }
        if ($estado) {
            $sql_pesquisa .= " AND estado LIKE :estado";
        }
    } elseif ($tipo_usuario === 'tutor') {
        // Pesquisa alunos
        $sql_pesquisa = "SELECT id, nome, foto_perfil, cidade, estado FROM Alunos WHERE nome LIKE :termo";
        if ($cidade) {
            $sql_pesquisa .= " AND cidade LIKE :cidade";
        }
        if ($estado) {
            $sql_pesquisa .= " AND estado LIKE :estado";
        }
    }

    // Prepara e executa a consulta de pesquisa
    $stmt_pesquisa = $conn->prepare($sql_pesquisa);
    $stmt_pesquisa->bindValue(':termo', '%' . $termo_busca . '%', PDO::PARAM_STR);

    if ($cidade) {
        $stmt_pesquisa->bindValue(':cidade', '%' . $cidade . '%', PDO::PARAM_STR);
    }
    if ($estado) {
        $stmt_pesquisa->bindValue(':estado', '%' . $estado . '%', PDO::PARAM_STR);
    }
    $stmt_pesquisa->execute();

    // Obtém os resultados
    $resultados_pesquisa = $stmt_pesquisa->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="ASSETS/CSS/style.css">
</head>
<body>

    <!-- Cabeçalho -->
    <header class="header">
        <img src="ASSETS/IMG/capa.png" alt="Capa do site">
    </header>

    <!-- Navegação -->
    <nav class="navbar">
        <a href="index.php">Home</a>
        <a href="sobre_nos.php">Sobre nós</a>
        <a href="dashboard.php">Dashboard</a> <!-- Alterado para uma única dashboard -->
        <a href="logout.php">Logout</a>
    </nav>

    <!-- Conteúdo Principal -->
    <main class="main-content">
    <section class="dashboard-section">

        <!-- Saudação -->
        <div class="signup-section">
            <h3>Bem-vindo(a) <?php echo ($tipo_usuario === 'aluno' ? 'Aluno(a), ' : 'Tutor(a), '); ?><?php echo htmlspecialchars($usuario['nome']); ?>!</h3>
        </div>
        <!-- fim Saudação -->

        <!-- Perfil do Usuário -->
        <section class="profile">
            <h1>Bem-vindo, <?php echo htmlspecialchars($usuario['nome']); ?>!</h1>
            <img src="uploads/<?php echo $usuario['foto_perfil']; ?>" alt="Foto do Perfil" class="profile-photo">
            <p><strong>Localização:</strong> <?php echo htmlspecialchars($usuario['cidade'] . ', ' . $usuario['estado']); ?></p>
            <p><strong>Tipo:</strong> <?php echo ucfirst($tipo_usuario); ?></p>
            <?php if ($tipo_usuario === 'tutor'): ?>
                <p><strong>Total de Curtidas:</strong> <?php echo $total_curtidas; ?></p>
            <?php endif; ?>
        </section>

        <!-- Mensagens Recentes -->
        <section class="messages">
            <h2>Mensagens Recentes</h2>
            <ul>
                <?php foreach ($mensagens as $mensagem): ?>
                    <li>
                        <p><strong><?php echo htmlspecialchars($mensagem['remetente_nome']); ?>:</strong> <?php echo htmlspecialchars($mensagem['mensagem']); ?></p>
                        <small><?php echo $mensagem['data_envio']; ?></small>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>

        <!-- Aulas Agendadas -->
        <section class="appointments">
            <h2>Aulas Agendadas</h2>
            <ul>
                <?php foreach ($aulas_agendadas as $aula): ?>
                    <li>
                        <p><strong><?php echo htmlspecialchars($aula['aluno_nome'] ?: $aula['tutor_nome']); ?> - Data: <?php echo $aula['data_hora']; ?></strong></p>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>

        <!-- Pesquisa de Alunos ou Tutores -->
        <section class="search">
            <h2>Pesquisa</h2>
            <form method="get">
                <input type="text" name="termo" placeholder="Nome do <?php echo ($tipo_usuario === 'aluno') ? 'Tutor' : 'Aluno'; ?>" required>
                <input type="text" name="cidade" placeholder="Cidade">
                <input type="text" name="estado" placeholder="Estado">
                <button type="submit" name="buscar">Buscar</button>
            </form>

            <?php if (isset($resultados_pesquisa)): ?>
                <h3>Resultados da Pesquisa</h3>
                <ul>
                    <?php foreach ($resultados_pesquisa as $resultado): ?>
                        <li>
                            <p><?php echo htmlspecialchars($resultado['nome']); ?> - <?php echo htmlspecialchars($resultado['cidade'] . ', ' . $resultado['estado']); ?></p>
                            <a href="about.php?id=<?php echo $resultado['id']; ?>">Ver Perfil</a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
            </section>
    </main>
    <!-- fim Conteúdo Principal -->

    <!-- Rodapé -->
    <footer class="footer">
        UNIVESP PI 2024
    </footer>

</body>
</html>
