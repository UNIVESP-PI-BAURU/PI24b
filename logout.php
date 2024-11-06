<?php
session_start();

// Exibe a mensagem de sucesso de logout, se houver
if (isset($_GET['logout']) && $_GET['logout'] == 'success') {
    echo '<p style="color: green;">Você foi desconectado com sucesso.</p>';
}

?>
