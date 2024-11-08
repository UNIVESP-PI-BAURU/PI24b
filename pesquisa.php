<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['id_usuario']) || !isset($_SESSION['tipo_usuario'])) {
    header("Location: login.php");
    exit();
}

require_once 'conexao.php'; // Inclui a conexão com o banco

$tipo_usuario = $_SESSION['tipo_usuario']; // Aluno ou Tutor
$tabela_pesquisa = ($tipo_usuario === 'aluno') ? 'Tutores' : 'Alunos';

// Inicializa os critérios de pesquisa com valores vazios
$idioma = $cidade = $estado = "";
$resultados = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Coleta os valores dos critérios de pesquisa
    $idioma = trim($_POST['idioma']);
    $cidade = trim($_POST['cidade']);
    $estado = trim($_POST['estado']);

    // Constrói a consulta SQL com os critérios informados
    $sql = "SELECT nome, foto_perfil, cidade, estado, idiomas FROM $tabela_pesquisa WHERE 1=1";
    $params = [];

    if (!empty($idioma)) {
        $sql .= " AND idiomas LIKE :idioma";
        $params[':idioma'] = '%' . $idioma . '%';
    }
    if (!empty($cidade)) {
        $sql .= " AND cidade = :cidade";
        $params[':cidade'] = $cidade;
    }
    if (!empty($estado)) {
        $sql .= " AND estado = :estado";
        $params[':estado'] = $estado;
    }

    $stmt = $conn->prepare($sql);
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    $stmt->execute();
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesquisa</title>
    <link rel="stylesheet" href="ASSETS/CSS/style.css">
</head>
<body>

    <header class="header">
        <img src="ASSETS/IMG/capa.png" alt="Imagem de Capa">
    </header>

    <nav class="navbar">
        <button onclick="window.location.href='index.php'">Home</button>
        <button onclick="window.location.href='dashboard.php'">Dashboard</button>
        <button onclick="window.location.href='logout.php'">Logout</button>
    </nav>

    <main class="main-content">
        <section class="search-section">
            <h2>Pesquisar <?php echo ($tipo_usuario === 'aluno') ? 'Tutores' : 'Alunos'; ?></h2>
            
            <form method="POST" action="pesquisa.php">
                <label for="idioma">Idioma:</label>
                <input type="text" id="idioma" name="idioma" value="<?php echo htmlspecialchars($idioma); ?>" placeholder="Ex: Inglês, Espanhol">

                <label for="cidade">Cidade:</label>
                <input type="text" id="cidade" name="cidade" value="<?php echo htmlspecialchars($cidade); ?>" placeholder="Ex: São Paulo">

                <label for="estado">Estado:</label>
                <input type="text" id="estado" name="estado" value="<?php echo htmlspecialchars($estado); ?>" placeholder="Ex: SP">
                
                <button type="submit">Pesquisar</button>
            </form>

            <?php if (!empty($resultados)): ?>
                <h3>Resultados da Pesquisa</h3>
                <div class="resultados-container">
                    <?php foreach ($resultados as $resultado): ?>
                        <div class="resultado-item">
                            <div class="foto-moldura">
                                <?php if (!empty($resultado['foto_perfil'])): ?>
                                    <img src="<?php echo htmlspecialchars($resultado['foto_perfil']); ?>" alt="Foto de Perfil" class="avatar-resultado">
                                <?php else: ?>
                                    <p>Sem foto</p>
                                <?php endif; ?>
                            </div>
                            <p><strong>Nome:</strong> <?php echo htmlspecialchars($resultado['nome']); ?></p>
                            <p><strong>Cidade:</strong> <?php echo htmlspecialchars($resultado['cidade']); ?></p>
                            <p><strong>Estado:</strong> <?php echo htmlspecialchars($resultado['estado']); ?></p>
                            <p><strong>Idiomas:</strong> <?php echo htmlspecialchars($resultado['idiomas']); ?></p>
                            <button onclick="window.location.href='about.php?id=<?php echo $resultado['id']; ?>&tipo=<?php echo ($tipo_usuario === 'aluno') ? 'tutor' : 'aluno'; ?>'">Ver Perfil Completo</button>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
                <p>Nenhum resultado encontrado para os critérios informados.</p>
            <?php endif; ?>
        </section>
    </main>

    <footer class="footer">
        UNIVESP PI 2024
    </footer>
    
</body>
</html>
