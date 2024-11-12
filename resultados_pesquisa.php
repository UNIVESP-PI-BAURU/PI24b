<?php
session_start();
require_once 'conexao.php'; 

// Verifica se o usuário está logado
if (!isset($_SESSION['id_usuario']) || !isset($_SESSION['tipo_usuario'])) {
    header("Location: login.php");
    exit();
}

// Define o tipo de usuário logado (aluno ou tutor)
$tipo_usuario = $_SESSION['tipo_usuario'];

// Define a tabela de pesquisa (alunos pesquisam tutores e tutores pesquisam alunos)
$tabela_pesquisa = ($tipo_usuario === 'aluno') ? 'Tutores' : 'Alunos';

// Obtém os critérios de pesquisa enviados pelo formulário
$idioma = isset($_GET['idioma']) ? $_GET['idioma'] : null;
$cidade = isset($_GET['cidade']) ? $_GET['cidade'] : null;
$estado = isset($_GET['estado']) ? $_GET['estado'] : null;

// Monta a query de pesquisa com base nos critérios
$query = "SELECT id, nome, cidade, estado, idiomas, foto_perfil FROM $tabela_pesquisa WHERE 1=1";
$params = [];

// Adiciona os parâmetros de busca
if ($idioma) {
    $query .= " AND idiomas LIKE :idioma";
    $params[':idioma'] = '%' . $idioma . '%';
}

if ($cidade) {
    $query .= " AND cidade = :cidade";
    $params[':cidade'] = $cidade;
}

if ($estado) {
    $query .= " AND estado = :estado";
    $params[':estado'] = $estado;
}

// Prepara e executa a consulta
$stmt = $conn->prepare($query);
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value);
}
$stmt->execute();
$resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados da Pesquisa</title>
    <link rel="stylesheet" href="ASSETS/CSS/style.css">
</head>
<body>

    <header class="header">
        <img src="ASSETS/IMG/capa.png" alt="Capa do site">
    </header>

    <nav class="navbar">
        <button onclick="window.location.href='index.php'">Home</button>
        <button onclick="window.location.href='dashboard.php'">Meu painel</button>
        <button onclick="window.location.href='logout.php'">Logout</button>
    </nav>

    <!-- Conteúdo Principal -->
    <main class="main-content">
        <section class="signup-section">
            <h3>Resultados da Pesquisa</h3>

            <?php if (count($resultados) > 0): ?>
                <ul>
                    <?php foreach ($resultados as $usuario): ?>
                        <li>
                            <div class="result-item">
                                <?php if (!empty($usuario['foto_perfil'])): ?>
                                    <img src="<?php echo htmlspecialchars($usuario['foto_perfil']); ?>" alt="Foto de Perfil" class="avatar">
                                <?php else: ?>
                                    <p>Sem foto de perfil</p>
                                <?php endif; ?>

                                <div class="result-info">
                                    <p><strong>Nome:</strong> <?php echo htmlspecialchars($usuario['nome']); ?></p>
                                    <p><strong>Cidade/Estado:</strong> <?php echo htmlspecialchars($usuario['cidade']) . ', ' . htmlspecialchars($usuario['estado']); ?></p>
                                    <p><strong>Idiomas:</strong> <?php echo htmlspecialchars($usuario['idiomas']); ?></p>
                                    <p>
                                        <!-- Botão 'Ver Mais' -->
                                        <button onclick="window.location.href='about.php?id=<?php echo $usuario['id']; ?>'">Ver Mais</button>
                                    </p>
                                </div>
                            </div>
                        </li>
                        <hr><!-- Separador entre resultados -->
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Nenhum resultado encontrado.</p>
            <?php endif; ?>

            <br><br>
            <!--Botão para retornar à Dashboard -->
            <button onclick="window.location.href='dashboard.php'">Retornar ao Meu painel</button>

        </section>
    </main>

    <!-- Rodapé -->
    <footer class="footer">
        <p>UNIVESP PI 2024</p>
        <p><a href="https://github.com/UNIVESP-PI-BAURU/PI24b.git" target="_blank">https://github.com/UNIVESP-PI-BAURU/PI24b.git</a></p>
    </footer>

</body>
</html>
