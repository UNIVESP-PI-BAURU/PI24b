<?php
// Inicie a sessão
session_start();

// Conecte-se ao banco de dados
include 'conexao.php';

// Verifique se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Prepare e execute a consulta
    $stmt = $conn->prepare("SELECT * FROM Tutores WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $usuario = $result->fetch_assoc();
        if (password_verify($senha, $usuario['senha'])) {
            // Autenticação bem-sucedida
            $_SESSION['id_usuario'] = $usuario['id_tutor'];
            $_SESSION['nome'] = $usuario['nome'];
            $_SESSION['tipo_usuario'] = 'tutor';

            error_log("Antes do redirecionamento para dashboard_tutor.php");
            header("Location: ./TUTORES/dashboard_tutor.php");
            exit();
        } else {
            echo "Senha incorreta.";
        }
    } else {
        echo "Usuário não encontrado.";
    }
} else {
    echo "Método de requisição inválido.";
}

// Feche a conexão
$conn->close();
?>
