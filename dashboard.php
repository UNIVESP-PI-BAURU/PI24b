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

// Debug: Exibe o tipo e nome do usuário
// echo "Tipo de usuário: $tipo_usuario<br>";
// echo "Nome de usuário: $nome_usuario<br>";
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
            <?php
                // Debug: Exibe o tipo de usuário no HTML
                // echo "<br>Debug Saudação: Nome: $nome_usuario - Tipo: $tipo_usuario";
            ?>
        </section>
        <!-- Fim Saudação -->

        <!-- complemento: Resumo Perfil -->
        <section class="signup-section">    
            <section class="perfil-resumo">
                <h4>Resumo do Perfil</h4>
                <?php
                    // Exibe informações adicionais do perfil (exemplo: foto, cidade, idioma)
                    // Aqui você pode adaptar para exibir as informações que você tiver, como cidade, idioma, etc.

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

        <!-- complemento: pesquisa -->
        <section class="signup-section">
            <section class="pesquisa">
                <h4>Pesquisar</h4>
                <button onclick="window.location.href='pesquisa.php';">Ir para Pesquisa</button>
            </section>
        </section>
        <!-- Fim pesquisa -->        

        <!-- complemento: Contratos -->
        <section class="signup-section">
            <section class="contratos">
                <h4>Contratos</h4>
                <?php
                    // Exibe os contratos dependendo do tipo de usuário
                    if ($tipo_usuario === 'aluno') {
                        // Código para exibir os contratos do aluno (Contratos pendentes, confirmados, etc)
                        $sql = "SELECT c.id, t.nome AS tutor_nome, c.status
                                FROM Contratos c
                                JOIN Tutores t ON c.id_tutor = t.id
                                WHERE c.id_aluno = ?"; // Ajuste com a consulta que você quer
                    } else {
                        // Código para exibir os contratos do tutor
                        $sql = "SELECT c.id, a.nome AS aluno_nome, c.status
                                FROM Contratos c
                                JOIN Alunos a ON c.id_aluno = a.id
                                WHERE c.id_tutor = ?"; // Ajuste com a consulta que você quer
                    }

                    // Preparar e executar a consulta
                    if ($stmt = $conn->prepare($sql)) {
                        $stmt->bind_param("i", $_SESSION['id_usuario']); // Usando id_usuario da sessão
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<p>Contrato ID: " . $row['id'] . " - " . ($tipo_usuario === 'aluno' ? 'Tutor(a): ' : 'Aluno(a): ') . $row[($tipo_usuario === 'aluno' ? 'tutor_nome' : 'aluno_nome')] . " - Status: " . $row['status'] . "</p>";
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
