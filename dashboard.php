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

// Recupera o ID do usuário para fazer consultas na tabela de Contratos
$id_usuario = $_SESSION['id_usuario'];
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
                    // Verifica se a variável de foto do usuário está definida
                    if (isset($_SESSION['foto_usuario']) && !empty($_SESSION['foto_usuario'])) {
                        $foto_usuario = $_SESSION['foto_usuario'];
                        echo "<img src='ASSETS/IMG/$foto_usuario' alt='Foto do usuário' class='avatar-dashboard'><br>";
                    } else {
                        echo "<p>Foto não disponível.</p>";  // Mensagem se a foto não estiver definida
                    }
                ?>
                <p>Nome: <?php echo htmlspecialchars($nome_usuario); ?></p>
            </section>
            <section class="perfil-completo">
                <button onclick="window.location.href='perfil.php';">Ver Perfil Completo</button>
            </section>
        </section>
        <!-- Fim Resumo Perfil -->

        <!-- complemento: Contratos -->
        <section class="signup-section">
            <section class="contratos">
                <h4>Contratos</h4>
                <?php
                    // Defina a consulta SQL dependendo do tipo de usuário
                    if ($tipo_usuario === 'aluno') {
                        // Exibe contratos do aluno
                        $sql = "SELECT c.id, t.nome AS tutor_nome, c.status
                                FROM Contratos c
                                JOIN Tutores t ON c.id_tutor = t.id
                                WHERE c.id_aluno = ?"; // Ajuste com a consulta que você quer
                    } else {
                        // Exibe contratos do tutor
                        $sql = "SELECT c.id, a.nome AS aluno_nome, c.status
                                FROM Contratos c
                                JOIN Alunos a ON c.id_aluno = a.id
                                WHERE c.id_tutor = ?"; // Ajuste com a consulta que você quer
                    }

                    // Preparar e executar a consulta
                    if ($stmt = $conn->prepare($sql)) {
                        // Passa o id do usuário (tutor ou aluno) para a consulta
                        $stmt->bind_param("i", $id_usuario);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        // Verifica se existem resultados
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                // Define as variáveis para o nome e o status
                                $nome_contratado = ($tipo_usuario === 'aluno') ? $row['tutor_nome'] : $row['aluno_nome'];
                                $status = $row['status'];
                                $contrato_id = $row['id'];

                                // Exibe os contratos
                                echo "<p>Contrato ID: $contrato_id - " . ($tipo_usuario === 'aluno' ? 'Tutor(a): ' : 'Aluno(a): ') . htmlspecialchars($nome_contratado) . " - Status: " . htmlspecialchars($status) . "</p>";

                                // Exibe botões de ação dependendo do status do contrato
                                if ($tipo_usuario === 'aluno' && $status === 'pendente') {
                                    // Se for aluno e o status for "pendente"
                                    echo "<button onclick='window.location.href=\"cancelar_contrato.php?id=$contrato_id\"'>Cancelar Contrato</button>";
                                } elseif ($tipo_usuario === 'tutor') {
                                    // Se for tutor
                                    if ($status === 'pendente') {
                                        echo "<button onclick='window.location.href=\"aceitar_contrato.php?id=$contrato_id\"'>Aceitar</button>";
                                        echo "<button onclick='window.location.href=\"negar_contrato.php?id=$contrato_id\"'>Negar</button>";
                                    } elseif ($status === 'confirmado') {
                                        echo "<button onclick='window.location.href=\"cancelar_contrato.php?id=$contrato_id\"'>Cancelar Contrato</button>";
                                    }
                                }
                            }
                        } else {
                            echo "<p>Não há contratos registrados.</p>";
                        }
                        $stmt->close();
                    }
                ?>
            </section>
        </section>
        <!-- Fim Contratos -->

    </main>
    <!-- Fim Conteúdo Principal -->

    <!-- Rodapé -->
    <footer class="footer">
        <p>UNIVESP PI 2024</p>
        <p><a href="https://github.com/UNIVESP-PI-BAURU/PI24b.git" target="_blank">https://github.com/UNIVESP-PI-BAURU/PI24b.git</a></p>
    </footer>

</body>
</html>
