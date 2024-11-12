<?php
// Conexão com o banco de dados
require_once 'conexao.php';

// Inicialização da sessão
session_start();

// Processamento da solicitação de recuperação de senha
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    // Verificando se o email existe no banco de dados
    $sql = "SELECT * FROM Alunos WHERE email = :email UNION SELECT * FROM Tutores WHERE email = :email";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        // Gerando um token único para a recuperação da senha
        $token = bin2hex(random_bytes(16)); // Gera um token aleatório de 32 caracteres

        // Salvando o token no banco de dados, para validar depois
        $sql = "INSERT INTO Recuperacao_Senha (email, token) VALUES (:email, :token)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':token', $token);
        $stmt->execute();

        // Enviar o e-mail com o link para redefinir a senha
        $link = "http://seusite.com/redefinir_senha.php?token=" . $token;

        // Função para enviar o e-mail
        $subject = "Recuperação de Senha - Conectando Interesses";
        $message = "Clique no link para redefinir sua senha: " . $link;
        $headers = "From: no-reply@seusite.com";

        if (mail($email, $subject, $message, $headers)) {
            $sucesso = "Um e-mail com as instruções foi enviado.";
        } else {
            $erro = "Erro ao enviar o e-mail. Tente novamente.";
        }
    } else {
        $erro = "E-mail não encontrado.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Senha</title>
    <link rel="stylesheet" href="ASSETS/CSS/style.css">
</head>
<body>
    <header class="header">
        <img src="ASSETS/IMG/capa.png" alt="Imagem de Capa">
    </header>

    <nav class="navbar">
        <button onclick="window.location.href='./index.php';">Home</button>
        <button onclick="window.location.href='./sobre_nos.php';">Sobre nós</button>
    </nav>

    <main class="main-content">
        <section class="signup-section">
            <h2>Recuperar Senha</h2>

            <?php if (isset($erro)): ?>
                <div class="error"><?= $erro ?></div>
            <?php endif; ?>
            <?php if (isset($sucesso)): ?>
                <div class="success"><?= $sucesso ?></div>
            <?php endif; ?>

            <form action="recuperar_senha.php" method="POST">
                <input type="email" name="email" placeholder="E-mail" required><br>
                <button type="submit" class="signup-button">Recuperar Senha</button>
            </form>

            <p><a href="login.php">Voltar para o login</a></p>
        </section>
    </main>

    <footer class="footer">
        <p>UNIVESP PI 2024</p>
        <p><a href="https://github.com/UNIVESP-PI-BAURU/PI24b.git" target="_blank">https://github.com/UNIVESP-PI-BAURU/PI24b.git</a></p>
    </footer>
</body>
</html>
