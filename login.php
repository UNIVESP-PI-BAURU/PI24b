<?php
// Conexão com o banco de dados
require_once 'conexao.php';

// Inicialização da sessão
session_start();

// Verificando se o usuário já está logado
if (isset($_SESSION['id_usuario']) && isset($_SESSION['tipo_usuario'])) {
    header('Location: index.php');
    exit;
}

// Processamento do login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $tipo = $_POST['tipo']; // Tipo: aluno ou tutor

    // Verificando se o tipo de usuário é 'aluno' ou 'tutor' e selecionando a tabela correspondente
    if ($tipo === 'aluno') {
        $sql = "SELECT * FROM Alunos WHERE email = :email";
    } else {
        $sql = "SELECT * FROM Tutores WHERE email = :email";
    }

    // Preparando a consulta
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    // Verificando se o usuário foi encontrado
    if ($stmt->rowCount() > 0) {
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verificando se a senha está correta
        if (password_verify($senha, $usuario['senha'])) {
            // Iniciando sessão e redirecionando para a página inicial ou perfil
            $_SESSION['id_usuario'] = ($tipo === 'aluno') ? $usuario['id_aluno'] : $usuario['id_tutor'];
            $_SESSION['tipo_usuario'] = $tipo;
            $_SESSION['nome_usuario'] = $usuario['nome'];
            header('Location: dashboard.php'); // Redireciona para o dashboard
            exit;
        } else {
            $erro = "Senha incorreta.";
        }
    } else {
        $erro = "Usuário não encontrado.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Conectando Interesses</title>
    <link rel="stylesheet" href="ASSETS/CSS/style.css">
</head>

<body>

    <!-- Cabeçalho -->
    <header class="header">
        <img src="ASSETS/IMG/capa.png" alt="Imagem de Capa">
    </header>

    <!-- Navegação -->
    <nav class="navbar">
        <button onclick="window.location.href='./index.php';">Home</button>
        <button onclick="window.location.href='./sobre_nos.php';">Sobre nós</button>
    </nav>
    <!-- Fim Navegação -->

    <!-- Conteúdo Principal -->
    <main class="main-content">
        <section class="signup-section">
            <h2>Login</h2>

            <!-- Exibe mensagens de erro/sucesso -->
            <?php if (isset($erro)): ?>
                <div class="error"><?= $erro ?></div>
            <?php endif; ?>

            <form action="login.php" method="POST">

                <!-- Tipo de usuário -->
                <div class="user-type">
                    <h3>Escolha o tipo de usuário:</h3>
                    <div>
                        <input type="radio" name="tipo" value="aluno" required> Aluno
                    </div>
                    <div>
                        <input type="radio" name="tipo" value="tutor" required> Tutor
                    </div>
                </div>

                <!-- Campos de login -->
                <input type="email" name="email" placeholder="E-mail" required><br>
                <input type="password" name="senha" placeholder="Senha" required><br>

                <button type="submit" class="signup-button">Entrar</button>
            </form>

            <p>Ainda não tem uma conta?</p>
            <button onclick="window.location.href='cadastro.php';">Cadastre-se aqui</button>
        </section>
    </main>

    <!-- Rodapé -->
    <footer class="footer">
        UNIVESP PI 2024
    </footer>

</body>

</html>
