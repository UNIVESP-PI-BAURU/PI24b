<?php
// Inicia a sessão
session_start();

// Remove todas as variáveis de sessão
$_SESSION = [];

// Destroi a sessão
session_destroy();

// Opcional: Limpa o cookie de sessão no navegador
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Redireciona para a página de login
header("Location: login.php");
exit();
?>
