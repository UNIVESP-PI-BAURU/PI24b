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
$id_usuario = $_SESSION['id_usuario']; // Garantindo que id_usuario esteja corretamente setado

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

        <!-- complemento: Pesquisa -->
        <section class="signup-section">
            <section class="pesquisa">
                <h4>Pesquisar</h4>
                <button onclick="window.location.href='pesquisa.php';">Ir para Pesquisa</button>
            </section>
        </section>
        <!-- Fim Pesquisa -->

        <!-- complemento: Contratos -->
        <section class="signup-section">
            <section class="contratos">
                <h4>Contratos</h4>
                <?php
                    // Exibe os contratos dependendo do tipo de usuário
                    if ($tipo_usuario === 'aluno') {
                        // Exibe contratos do aluno
                        $sql = "SELECT c.id, t.nome AS tutor_nome, c.status
                                FROM Contratos c
                                JOIN Tutores t ON c.id_tutor = t.id
                                WHERE c.id_aluno = :id_usuario"; // Ajuste com a consulta que você quer
                    } else {
                        // Exibe contratos do tutor
                        $sql = "SELECT c.id, a.nome AS aluno_nome, c.status
                                FROM Contratos c
                                JOIN Alunos a ON c.id_aluno = a.id
                                WHERE c.id_tutor = :id_usuario"; // Ajuste com a consulta que você quer
                    }

                    // Preparar e executar a consulta
                    try {
                        $stmt = $conn->prepare($sql);
                        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT); // Usando id_usuario da sessão
                        $stmt->execute();
                        $result = $stmt->fetchAll(PDO::FETCH_ASSOC); // Usar fetchAll para obter todas as linhas de uma vez

                        if (count($result) > 0) {
                            foreach ($result as $row) {
                                $nome_contratado = ($tipo_usuario === 'aluno') ? $row['tutor_nome'] : $row['aluno_nome'];
                                $status = $row['status'];
                                $contrato_id = $row['id'];

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
                    } catch (PDOException $e) {
                        echo "Erro: " . $e->getMessage();
                    }
                ?>
            </section>
        </section>
        <!-- Fim Contratos -->

        <!-- complemento: Últimas Mensagens -->
        <section class="signup-section">
            <section class="mensagens">
                <h4>Últimas Mensagens</h4>
                <?php
                    // Consulta para pegar as últimas 5 mensagens trocadas
                    $sql_mensagens = "SELECT m.id, m.mensagem AS conteudo, m.data_envio, m.status_leitura, 
                        COALESCE(a.nome, t.nome) AS usuario_nome
                        FROM Mensagens m
                        LEFT JOIN Alunos a ON a.id = m.id_remetente OR a.id = m.id_destinatario
                        LEFT JOIN Tutores t ON t.id = m.id_remetente OR t.id = m.id_destinatario
                        WHERE (m.id_remetente = :id_usuario OR m.id_destinatario = :id_usuario)
                        ORDER BY m.data_envio DESC LIMIT 5"; // Limitar para as últimas 5 mensagens


                    try {
                        $stmt_mensagens = $conn->prepare($sql_mensagens);
                        $stmt_mensagens->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
                        $stmt_mensagens->bindParam(':tipo_usuario', $tipo_usuario, PDO::PARAM_STR);
                        $stmt_mensagens->execute();
                        $result_mensagens = $stmt_mensagens->fetchAll(PDO::FETCH_ASSOC);

                        if (count($result_mensagens) > 0) {
                            foreach ($result_mensagens as $mensagem) {
                                $nome_usuario_mensagem = $mensagem['usuario_nome'];
                                $conteudo_mensagem = htmlspecialchars($mensagem['mensagem']);
                                $data_envio = $mensagem['data_envio'];
                                $status_leitura = $mensagem['status_leitura'];

                                // Exibe o conteúdo da mensagem
                                echo "<p><strong>$nome_usuario_mensagem</strong>: $conteudo_mensagem";
                                echo "<br><small>Enviada em: " . date('d/m/Y H:i', strtotime($data_envio)) . "</small></p>";

                                // Indicador de mensagem não lida
                                if ($status_leitura == 'não_lida') {
                                    echo "<p><em>Mensagem não lida</em></p>";
                                }
                            }
                        } else {
                            echo "<p>Não há mensagens recentes.</p>";
                        }
                    } catch (PDOException $e) {
                        echo "Erro: " . $e->getMessage();
                    }
                ?>
                <button onclick="window.location.href='conversa.php';">Ver todas as mensagens</button>
            </section>
        </section>
        <!-- Fim Últimas Mensagens -->

    </main>
    <!-- Fim Conteúdo Principal -->

    <!-- Rodapé -->
    <footer class="footer">
        <p>UNIVESP PI 2024</p>
        <p><a href="https://github.com/UNIVESP-PI-BAURU/PI24b.git" target="_blank">https://github.com/UNIVESP-PI-BAURU/PI24b.git</a></p>
    </footer>

</body>
</html>
