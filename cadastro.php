<?php
// Conexão com o banco de dados
require_once 'conexao.php'; // Certifique-se de que o arquivo de conexão com o banco de dados esteja correto

// Inicialização da sessão
session_start();

// Verificando se o usuário já está logado (não pode acessar a página de cadastro se estiver logado)
if (isset($_SESSION['id_usuario']) && isset($_SESSION['tipo_usuario'])) {
    header('Location: index.php');
    exit;
}

// Processamento do cadastro
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validação dos dados de entrada
    $nome = isset($_POST['nome']) ? $_POST['nome'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $senha = isset($_POST['senha']) ? $_POST['senha'] : '';
    $idioma = isset($_POST['idioma']) ? $_POST['idioma'] : '';
    $tipo = isset($_POST['tipo']) ? $_POST['tipo'] : '';

    // Verificando se os campos estão preenchidos
    if (empty($nome) || empty($email) || empty($senha) || empty($idioma) || empty($tipo)) {
        $erro = "Todos os campos são obrigatórios!";
    } else {
        // Validar se o email já existe no banco
        $sql = "SELECT * FROM Alunos WHERE email = :email UNION SELECT * FROM Tutores WHERE email = :email";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $erro = "Este e-mail já está registrado. Tente outro.";
        } else {
            // Cadastro do usuário no banco
            if ($tipo === 'aluno') {
                $sql = "INSERT INTO Alunos (email, senha, nome, idiomas, tipo) VALUES (:email, :senha, :nome, :idioma, :tipo)";
            } else {
                $sql = "INSERT INTO Tutores (email, senha, nome, idiomas, tipo) VALUES (:email, :senha, :nome, :idioma, :tipo)";
            }

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':senha', password_hash($senha, PASSWORD_DEFAULT)); // Criptografando a senha
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':idioma', $idioma);
            $stmt->bindParam(':tipo', $tipo);

            if ($stmt->execute()) {
                $_SESSION['cadastro_sucesso'] = true;
                header('Location: login.php');
                exit;
            } else {
                $erro = "Erro ao cadastrar. Tente novamente.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Conectando Interesses</title>
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
            <h2>Cadastro</h2>

            <!-- Exibe mensagens de erro/sucesso -->
            <?php if (isset($erro)): ?>
                <div class="error"><?= $erro ?></div>
            <?php endif; ?>

            <form action="cadastro.php" method="POST">
                
                <!-- Tipo de usuário fixo no início -->
                <div class="user-type">
                    <h3>Escolha o tipo de usuário:</h3>
                    <div>
                        <input type="radio" name="tipo" value="aluno" required> Aluno
                    </div>
                    <div>
                        <input type="radio" name="tipo" value="tutor" required> Tutor
                    </div>
                </div>
                
                <!-- Campos de cadastro -->
                <input type="text" name="nome" placeholder="Nome Completo" required><br>
                <input type="email" name="email" placeholder="E-mail" required><br>
                <input type="password" name="senha" placeholder="Senha" required><br>
                <input type="text" name="idioma" placeholder="Idioma" required><br>

                <button type="submit" class="signup-button">Cadastrar</button>
            </form>
        </section>
    </main>

    <!-- Rodapé -->
    <footer class="footer">
        <p>UNIVESP PI 2024</p>
        <p><a href="https://github.com/UNIVESP-PI-BAURU/PI24b.git" target="_blank">https://github.com/UNIVESP-PI-BAURU/PI24b.git</a></p>
    </footer>

</body>
</html>
