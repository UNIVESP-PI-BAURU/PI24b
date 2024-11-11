<?php
session_start();
require_once 'conexao.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

$id_remetente = $_SESSION['id_usuario']; // Usuário logado
$id_destinatario = $_POST['id_destinatario']; // ID do tutor (destinatário)

if ($id_destinatario) {
    // Cria uma nova conversa na tabela "Conversas"
    $sql_iniciar_conversa = "INSERT INTO Conversas (id_remetente, id_destinatario) VALUES (:id_remetente, :id_destinatario)";
    $stmt_iniciar_conversa = $conn->prepare($sql_iniciar_conversa);
    $stmt_iniciar_conversa->bindParam(':id_remetente', $id_remetente, PDO::PARAM_INT);
    $stmt_iniciar_conversa->bindParam(':id_destinatario', $id_destinatario, PDO::PARAM_INT);
    
    if ($stmt_iniciar_conversa->execute()) {
        // Após inserir a conversa, recupera o ID da nova conversa
        $id_conversa = $conn->lastInsertId();

        // Redireciona para a página do chat real
        header("Location: conversa.php?id_conversa=$id_conversa");
        exit();
    } else {
        echo "Erro ao iniciar o chat.";
    }
} else {
    echo "Erro: Destinatário não encontrado.";
}
?>
