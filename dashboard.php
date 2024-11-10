<?php
// Conexão com o banco de dados
require_once 'conexao.php';

// Inicialização da sessão
session_start();

// Verifica se o usuário está logado e redireciona para login se não estiver
if (!isset($_SESSION['id_usuario']) || !isset($_SESSION['tipo_usuario'])) {
    echo "Usuário não logado! Redirecionando para login..."; // Debug
    header("Location: login.php"); // Redireciona para login
    exit(); // Evita que o código continue
}

// Define o tipo de usuário e carrega o nome e foto do usuário
$tipo_usuario = $_SESSION['tipo_usuario']; // 'aluno' ou 'tutor'

// Consulta para pegar os dados do aluno ou tutor
if ($tipo_usuario === 'aluno') {
    $sql_usuario = "SELECT nome, foto_perfil FROM Alunos WHERE id_aluno = ?";
    $campo_id = 'id_aluno';
} else {
    $sql_usuario = "SELECT nome, foto_perfil FROM Tutores WHERE id_tutor = ?";
    $campo_id = 'id_tutor';
}

// Prepara a consulta e executa
$stmt = $conn->prepare($sql_usuario);
$stmt->bindValue(1, $_SESSION['id_usuario'], PDO::PARAM_INT);
$stmt->execute();
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

// Carrega o nome e foto do usuário
$nome_usuario = $usuario['nome'] ?? 'Visitante';
$foto_usuario = $usuario['foto_perfil'] ?? 'default.jpg';  // Foto padrão se não houver foto

// Lógica para pegar as últimas 3 aulas e as próximas 5 aulas
// Últimas 3 aulas
$sql_aulas = "SELECT * FROM Aulas WHERE $campo_id = ? ORDER BY data_aula DESC LIMIT 3";
$stmt = $conn->prepare($sql_aulas);
$stmt->bindValue(1, $_SESSION['id_usuario'], PDO::PARAM_INT);
$stmt->execute();
$result_aulas = $stmt->fetchAll(PDO::FETCH_ASSOC); // Alterado para fetchAll() para pegar todos os resultados
$ultimas_aulas = $result_aulas;

// Próximas 5 aulas
$sql_proximas_aulas = "SELECT * FROM Aulas WHERE $campo_id = ? AND data_aula > NOW() ORDER BY data_aula ASC LIMIT 5";
$stmt = $conn->prepare($sql_proximas_aulas);
$stmt->bindValue(1, $_SESSION['id_usuario'], PDO::PARAM_INT);
$stmt->execute();
$result_proximas_aulas = $stmt->fetchAll(PDO::FETCH_ASSOC); // Alterado para fetchAll() para pegar todos os resultados
$proximas_aulas = $result_proximas_aulas;
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

        <!-- complemento: Saudação -->
        <section class="signup-section">
            <h4>Bem-vindo(a), <?php echo htmlspecialchars($nome_usuario); ?>! Você é um(a) <?php echo ($tipo_usuario === 'aluno' ? 'Aluno(a)' : 'Tutor(a)'); ?>.</h4>
        </section>
        <!-- Fim Saudação -->

        <!-- complemento: Resumo Perfil -->
        <section class="signup-section">    
            <section class="perfil-resumo">
                <h4>Resumo do Perfil</h4>
                <?php
                    // Exibe a foto de perfil, se houver
                    if (!empty($foto_usuario)) {
                        echo "<img src='ASSETS/IMG/$foto_usuario' alt='Foto do usuário' class='avatar-dashboard'><br>";
                    }
                ?>
            </section>
            <section class="perfil-completo">
                <button onclick="window.location.href='perfil.php';">Ver Perfil Completo</button>
            </section>
        </section>
        <!-- Fim Resumo Perfil -->

        <!-- complemento: Aulas -->
        <section class="signup-section">
            <section class="aulas">
                <h4>Aulas</h4>
                
                <?php if ($tipo_usuario === 'tutor'): ?>
                    <!-- Botão para o tutor adicionar a disponibilidade -->
                    <button onclick="window.location.href='editar_disponibilidade.php';">Adicionar Disponibilidade</button>
                    <hr>
                    <!-- Botão para o tutor ver seus agendamentos -->
                    <button onclick="window.location.href='agendamentos.php';">Ver Agendamentos</button>
                <?php elseif ($tipo_usuario === 'aluno'): ?>
                    <!-- Botão para o aluno agendar uma aula -->
                    <button onclick="window.location.href='agendar_aula.php';">Agendar Aula</button>
                    <hr><!-- separador do complemento -->
                <?php endif; ?>

                <h5>Últimas 3 Aulas</h5>
                <ul>
                    <?php foreach ($ultimas_aulas as $aula): ?>
                        <li>
                            <strong><?php echo htmlspecialchars($aula['titulo']); ?></strong><br>
                            Data: <?php echo htmlspecialchars($aula['data_aula']); ?><br>
                            Local: <?php echo htmlspecialchars($aula['local']); ?><br>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <h5>Próximas 5 Aulas</h5>
                <ul>
                    <?php foreach ($proximas_aulas as $aula): ?>
                        <li>
                            <strong><?php echo htmlspecialchars($aula['titulo']); ?></strong><br>
                            Data: <?php echo htmlspecialchars($aula['data_aula']); ?><br>
                            Local: <?php echo htmlspecialchars($aula['local']); ?><br>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </section>
        </section>
        <!-- Fim Aulas -->

    </main>
    <!-- Fim Conteúdo Principal -->

    <!-- Rodapé -->
    <footer class="footer">
        <p>UNIVESP PI 2024</p>
        <p><a href="https://github.com/UNIVESP-PI-BAURU/PI24b.git" target="_blank">https://github.com/UNIVESP-PI-BAURU/PI24b.git</a></p>
    </footer>

</body>
</html>
