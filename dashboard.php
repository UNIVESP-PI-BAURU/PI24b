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

// Define o tipo de usuário e carrega o nome do usuário da sessão
$tipo_usuario = $_SESSION['tipo_usuario']; // 'aluno' ou 'tutor'
$nome_usuario = $_SESSION['nome_usuario'] ?? 'Visitante'; // Nome do usuário ou "Visitante" se não estiver definido

// Lógica para pegar as últimas 3 aulas e as próximas 5 aulas
$sql_aulas = "SELECT * FROM Aulas WHERE id_usuario = ? ORDER BY data_aula DESC LIMIT 3";
$stmt = $conn->prepare($sql_aulas);
$stmt->bindValue(1, $_SESSION['id_usuario'], PDO::PARAM_INT); // Correção aqui: Usando bindValue para PDO
$stmt->execute();
$result_aulas = $stmt->get_result();
$ultimas_aulas = [];
while ($row = $result_aulas->fetch_assoc()) {
    $ultimas_aulas[] = $row;
}

// Próximas 5 aulas
$sql_proximas_aulas = "SELECT * FROM Aulas WHERE id_usuario = ? AND data_aula > NOW() ORDER BY data_aula ASC LIMIT 5";
$stmt = $conn->prepare($sql_proximas_aulas);
$stmt->bindValue(1, $_SESSION['id_usuario'], PDO::PARAM_INT); // Correção aqui também: Usando bindValue para PDO
$stmt->execute();
$result_proximas_aulas = $stmt->get_result();
$proximas_aulas = [];
while ($row = $result_proximas_aulas->fetch_assoc()) {
    $proximas_aulas[] = $row;
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
                    if (isset($_SESSION['foto_usuario']) && !empty($_SESSION['foto_usuario'])) {
                        $foto_usuario = $_SESSION['foto_usuario'];
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
