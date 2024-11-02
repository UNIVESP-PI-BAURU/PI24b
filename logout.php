<?php
// Inicia a sessão
session_start();

// Remove todas as variáveis de sessão
$_SESSION = [];

// Destroi a sessão
session_destroy();

// Mensagem de logout
$_SESSION['success'] = "Você foi desconectado com sucesso.";

// Redireciona para a página de login
header("Location: login.php");
exit();
?>
