<?php
// Conexão com o banco de dados
require_once 'conexao.php';

// Inicialização da sessão
session_start();

// Verificando se o token existe na URL
if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Verificando se o token é válido
    $sql = "SELECT * FROM Recuperacao_Senha WHERE token = :token";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':token', $token);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        // Processamento da redefinição de senha
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nova_senha = $_POST['nova_senha'];
            $confirmar_senha = $_POST['confirmar_senha'];

            if ($nova_senha === $confirmar_senha) {
                // Atualizando a senha no banco de dados
                $sql = "UPDATE Alunos SET senha = :senha WHERE email = :email
                        UNION
                        SELECT * FROM Tutores WHERE email = :email";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':senha', password_hash($nova_senha, PASSWORD_DEFAULT));
                $stmt->bindParam(':email', $usuario['email']);
                $stmt->execute();

                // Remover o token após a redefinição
                $sql = "DELETE FROM Recuperacao_Senha WHERE token = :token";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':token', $token);
                $stmt->execute();

                $sucesso = "Senha redefinida com sucesso. Você pode agora fazer login.";
            } else {
                $erro = "As senhas não coincidem.";
            }
        }
    } else {
        $erro = "Token inválido ou expirado.";
    }
} else {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir Senha</title>
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
            <h2>Redefinir Senha</h2>

            <?php if (isset($erro)): ?>
                <div class="error"><?= $erro ?></div>
            <?php endif; ?>
            <?php if (isset($sucesso)): ?>
                <div class="success"><?= $sucesso ?></div>
            <?php endif; ?>

            <form action="redefinir_senha.php?token=<?= $token ?>" method="POST">
                <input type="password" name="nova_senha" placeholder="Nova Senha" required><br>
                <input type="password" name="confirmar_senha" placeholder="Confirmar Senha" required><br>
                <button type="submit" class="signup-button">Redefinir Senha</button>
            </form>
        </section>
    </main>

    <footer class="footer">
        <p>UNIVESP PI 2024</p>
        <p><a href="https://github.com/UNIVESP-PI-BAURU/PI24b.git" target="_blank">https://github.com/UNIVESP-PI-BAURU/PI24b.git</a></p>
    </footer>
</body>
</html>
