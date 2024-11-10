<?php
// Conexão com o banco de dados
require_once 'conexao.php'; // Certifique-se que o arquivo de conexão define $conn corretamente

// Inicialização da sessão
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['id_usuario']) || !isset($_SESSION['tipo_usuario'])) {
    header("Location: login.php?msg=precisa-logar");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];

// Consulta os dados do usuário
$query = "SELECT * FROM " . ($_SESSION['tipo_usuario'] == 'aluno' ? 'Alunos' : 'Tutores') . " WHERE id = :id_usuario";
$stmt = $conn->prepare($query);
$stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
$stmt->execute();
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

// Se o usuário não for encontrado, redireciona para o login
if (!$usuario) {
    header("Location: login.php?msg=usuario-nao-encontrado");
    exit();
}

// Processa o envio do formulário de edição
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = htmlspecialchars($_POST['nome']);
    $email = htmlspecialchars($_POST['email']);
    $idiomas = htmlspecialchars($_POST['idiomas']);
    $biografia = htmlspecialchars($_POST['biografia']);
    $cidade = htmlspecialchars($_POST['cidade']);
    $estado = htmlspecialchars($_POST['estado']);

    // Processar a foto de perfil, se houver
    if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] == 0) {
        $foto_perfil = processarFoto($_FILES['foto_perfil']);
        if (is_string($foto_perfil)) {
            echo "<p class='error-message'>$foto_perfil</p>";
        }
    } else {
        $foto_perfil = $usuario['foto_perfil']; // Mantém a foto existente caso não tenha sido enviada uma nova
    }

    // Atualiza os dados no banco
    $update_query = "UPDATE " . ($_SESSION['tipo_usuario'] == 'aluno' ? 'Alunos' : 'Tutores') . " SET nome = :nome, email = :email, idiomas = :idiomas, biografia = :biografia, cidade = :cidade, estado = :estado, foto_perfil = :foto_perfil WHERE id = :id_usuario";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bindParam(':nome', $nome);
    $update_stmt->bindParam(':email', $email);
    $update_stmt->bindParam(':idiomas', $idiomas);
    $update_stmt->bindParam(':biografia', $biografia);
    $update_stmt->bindParam(':cidade', $cidade);
    $update_stmt->bindParam(':estado', $estado);
    $update_stmt->bindParam(':foto_perfil', $foto_perfil);
    $update_stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);

    if ($update_stmt->execute()) {
        // Redireciona para perfil.php após atualização bem-sucedida
        header("Location: perfil.php");
        exit();
    } else {
        echo "<p class='error-message'>Erro ao atualizar perfil.</p>";
    }
}

// Processa a exclusão de conta
if (isset($_GET['excluir']) && $_GET['excluir'] == '1') {
    // Deleta o usuário da tabela correspondente
    $delete_query = "DELETE FROM " . ($_SESSION['tipo_usuario'] == 'aluno' ? 'Alunos' : 'Tutores') . " WHERE id = :id_usuario";
    $delete_stmt = $conn->prepare($delete_query);
    $delete_stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);

    if ($delete_stmt->execute()) {
        // Exclui a sessão e redireciona para a página de login com mensagem de conta excluída
        session_destroy();
        header("Location: login.php?msg=conta-excluida");
        exit();
    } else {
        echo "<p class='error-message'>Erro ao excluir conta. Tente novamente mais tarde.</p>";
    }
}

// Função para validar e processar a foto
function processarFoto($foto_perfil) {
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    $max_size = 5 * 1024 * 1024; // 5 MB
    $target_dir = 'uploads/';
    
    // Verifica se o tipo do arquivo é permitido
    if (!in_array($foto_perfil['type'], $allowed_types)) {
        return "Tipo de arquivo não permitido. Apenas JPG, PNG e GIF são permitidos.";
    }
    
    // Verifica se o arquivo é muito grande
    if ($foto_perfil['size'] > $max_size) {
        return "Arquivo muito grande. O tamanho máximo permitido é 5MB.";
    }
    
    // Gera um nome único para o arquivo
    $target_file = $target_dir . uniqid() . '-' . basename($foto_perfil['name']);
    
    // Move o arquivo para o diretório de uploads
    if (move_uploaded_file($foto_perfil['tmp_name'], $target_file)) {
        return $target_file;
    } else {
        return "Erro ao mover o arquivo para o diretório de uploads.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil</title>
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
    <section class="signup-section">
        
        <h3>Editar Perfil</h3>

        <!-- Formulário de edição -->
        <form action="editar_perfil.php" method="POST" enctype="multipart/form-data">
            <label for="nome">Nome:</label>
            <input type="text" name="nome" value="<?php echo htmlspecialchars($usuario['nome']); ?>" >
            <br>
            <label for="email">Email:</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" >
            <br>
            <label for="idiomas">Idiomas:</label>
            <input type="text" name="idiomas" value="<?php echo htmlspecialchars($usuario['idiomas']); ?>" >
            <br>
            <label for="biografia">Biografia:</label>
            <textarea name="biografia" ><?php echo htmlspecialchars($usuario['biografia']); ?></textarea>
            <br>
            <label for="cidade">Cidade:</label>
            <input type="text" name="cidade" value="<?php echo htmlspecialchars($usuario['cidade']); ?>" >
            <br>
            <label for="estado">Estado:</label>
            <input type="text" name="estado" value="<?php echo htmlspecialchars($usuario['estado']); ?>" >
            <br>
            <label for="foto_perfil">Foto de Perfil:</label>
            <input type="file" name="foto_perfil">
            <br>
            <button type="submit">Salvar Alterações</button>
            <button type="button" onclick="confirmarExclusao()">Excluir Conta</button>
        </form>
    </section>
</main>
<!-- fim Conteúdo Principal -->

<!-- Rodapé -->
<footer class="footer">
    <p>UNIVESP PI 2024</p>
    <p><a href="https://github.com/UNIVESP-PI-BAURU/PI24b.git" target="_blank">https://github.com/UNIVESP-PI-BAURU/PI24b.git</a></p>
</footer>

<script>
    function confirmarExclusao() {
        if (confirm("Você tem certeza que deseja excluir sua conta? Esta ação não pode ser desfeita.")) {
            window.location.href = "editar_perfil.php?excluir=1";
        }
    }
</script>

</body>
</html>
