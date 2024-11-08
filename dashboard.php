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
        <button onclick="window.location.href='index.php';">Home</button>
        <button onclick="window.location.href='sobre_nos.php';">Sobre nós</button>
        <button onclick="window.location.href='dashboard.php';">Dashboard</button>
        <button onclick="window.location.href='logout.php';">Logout</button>
    </nav>
    <!-- Fim Navegação -->

    <!-- Conteúdo Principal -->
    <main class="main-content">
        <section class="dashboard-section">

        <!-- Saudação -->
        <div class="signup-section">
            <h3>Bem-vindo(a) <?php echo ($tipo_usuario === 'aluno' ? 'Aluno(a), ' : 'Tutor(a), '); ?><?php echo htmlspecialchars($usuario['nome']); ?>!</h3>
        </div>
        <!-- fim Saudação -->

        <!-- Perfil -->
        <div class="signup-section" style="display: flex; align-items: center; margin-bottom: 20px;">
            <div style="flex: 1;">
                <div class="foto-moldura-dashboard">
                    <?php if (!empty($usuario['foto_perfil'])): ?>
                        <img src="<?php echo htmlspecialchars($usuario['foto_perfil']); ?>" alt="Avatar" class="avatar-dashboard">
                    <?php else: ?>
                        <p>Não há foto</p>
                    <?php endif; ?>
                </div>
            </div>
            <div style="flex: 2; padding-left: 10px;">
                <p><?php echo ($tipo_usuario === "tutor" ? "Tutor(a): " : "Aluno(a): ") . htmlspecialchars($usuario['nome']); ?></p>
                
                <!-- Exibe cidade e estado, caso existam -->
                <?php if (!empty($usuario['cidade']) || !empty($usuario['estado'])): ?>
                    <p>
                        <?php 
                            echo htmlspecialchars($usuario['cidade']) ? htmlspecialchars($usuario['cidade']) . ', ' : ''; 
                            echo htmlspecialchars($usuario['estado']) ? htmlspecialchars($usuario['estado']) : ''; 
                        ?>
                    </p>
                <?php endif; ?>
                
                <!-- Atualize o botão para redirecionar com o ID do usuário -->
                <button onclick="window.location.href='perfil.php?id=<?php echo $id_usuario; ?>';">Ver meu perfil</button>

            </div>
        </div>
        <!-- fim Perfil -->

        <!-- Aulas Agendadas -->
        <section class="signup-section aulas-agendadas">
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
        <!-- Fim Aulas Agendadas -->

        <!-- Chat (Últimas 5 mensagens) -->
        <section class="signup-section chat-mensagens">
            <h3>Últimas Mensagens</h3>
            <ul>
                <?php foreach ($mensagens as $mensagem): ?>
                    <li>
                        <p><strong><?php echo htmlspecialchars($mensagem['remetente_nome']); ?>:</strong> <?php echo htmlspecialchars($mensagem['mensagem']); ?></p>
                        <p><small><?php echo htmlspecialchars($mensagem['data_envio']); ?></small></p>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>
        <!-- Fim Chat -->

        </section>
    </main>
    <!-- fim Conteúdo Principal -->

    <!-- Rodapé -->
    <footer class="footer">
        UNIVESP PI 2024
    </footer>

</body>
</html>
