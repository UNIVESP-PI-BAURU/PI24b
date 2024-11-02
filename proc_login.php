<?php
// Inicia a sessão no início do script
session_start();

// Inclui o arquivo de conexão com o banco de dados
require_once 'conexao.php';

// Verifica se foi enviado um formulário de login
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"])) {
    // Recupera os dados do formulário
    $email = htmlspecialchars(trim($_POST["email"])); // Sanitização
    $senha = $_POST["senha"];
    $tipo_usuario = $_POST["tipo_usuario"]; // Tipo de usuário (aluno ou tutor)

    // Debug: Exibir os dados recebidos
    error_log("Dados do login: Email: $email, Tipo: $tipo_usuario");

    // Define a tabela do banco de dados com base no tipo de usuário
    $tabela_usuario = ($tipo_usuario == "aluno") ? "Alunos" : "Tutores";

    // Verifica se o usuário existe no banco de dados
    $sql = "SELECT * FROM $tabela_usuario WHERE email = :email";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":email", $email);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    // Debug: Verificando se o usuário foi encontrado
    if ($usuario) {
        error_log("Usuário encontrado: " . print_r($usuario, true));

        // Verifica a senha usando password_verify
        if (password_verify($senha, $usuario['senha'])) {
            // Armazena o ID do usuário e o nome na sessão
            $id_usuario = $usuario['id']; // ID da tabela, que é o mesmo para ambos os tipos
            $_SESSION['id_' . $tipo_usuario] = $id_usuario;
            $_SESSION['nome'] = $usuario['nome']; // Armazena o nome na mesma variável para ambos os tipos

            // Mensagem de sucesso
            $_SESSION['success'] = "Login realizado com sucesso!";

            // Redireciona para a dashboard correspondente
            if ($tipo_usuario == "aluno") {
                header("Location: ALUNOS/dashboard_aluno.php");
            } else {
                header("Location: TUTORES/dashboard_tutor.php");
            }
            exit();
        } else {
            // Senha incorreta
            $_SESSION['error'] = "Senha incorreta. Por favor, tente novamente.";
            header("Location: login.php"); // Redireciona de volta para o login
            exit();
        }
    } else {
        // Usuário não encontrado
        $_SESSION['error'] = "Usuário não encontrado. Por favor, verifique o email e o tipo de usuário.";
        header("Location: login.php"); // Redireciona de volta para o login
        exit();
    }
} else {
    // Se não for uma requisição POST
    $_SESSION['error'] = "Método de requisição inválido.";
    header("Location: login.php");
    exit();
}
?>
<?php
// Inicia a sessão no início do script
session_start();

// Inclui o arquivo de conexão com o banco de dados
require_once 'conexao.php';

// Verifica se foi enviado um formulário de login
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"])) {
    // Recupera os dados do formulário
    $email = htmlspecialchars(trim($_POST["email"])); // Sanitização
    $senha = $_POST["senha"];
    $tipo_usuario = $_POST["tipo_usuario"]; // Tipo de usuário (aluno ou tutor)

    // Debug: Exibir os dados recebidos
    error_log("Dados do login: Email: $email, Tipo: $tipo_usuario");

    // Define a tabela do banco de dados com base no tipo de usuário
    $tabela_usuario = ($tipo_usuario == "aluno") ? "Alunos" : "Tutores";

    // Verifica se o usuário existe no banco de dados
    $sql = "SELECT * FROM $tabela_usuario WHERE email = :email";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":email", $email);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    // Debug: Verificando se o usuário foi encontrado
    if ($usuario) {
        error_log("Usuário encontrado: " . print_r($usuario, true));

        // Verifica a senha usando password_verify
        if (password_verify($senha, $usuario['senha'])) {
            // Armazena o ID do usuário e o nome na sessão
            $id_usuario = $usuario['id']; // ID da tabela, que é o mesmo para ambos os tipos
            $_SESSION['id_' . $tipo_usuario] = $id_usuario;
            $_SESSION['nome'] = $usuario['nome']; // Armazena o nome na mesma variável para ambos os tipos

            // Mensagem de sucesso
            $_SESSION['success'] = "Login realizado com sucesso!";

            // Redireciona para a dashboard correspondente
            if ($tipo_usuario == "aluno") {
                header("Location: ALUNOS/dashboard_aluno.php");
            } else {
                header("Location: TUTORES/dashboard_tutor.php");
            }
            exit();
        } else {
            // Senha incorreta
            $_SESSION['error'] = "Senha incorreta. Por favor, tente novamente.";
            header("Location: login.php"); // Redireciona de volta para o login
            exit();
        }
    } else {
        // Usuário não encontrado
        $_SESSION['error'] = "Usuário não encontrado. Por favor, verifique o email e o tipo de usuário.";
        header("Location: login.php"); // Redireciona de volta para o login
        exit();
    }
} else {
    // Se não for uma requisição POST
    $_SESSION['error'] = "Método de requisição inválido.";
    header("Location: login.php");
    exit();
}
?>
