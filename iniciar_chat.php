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
    // Gera um ID para a nova conversa (caso você queira gerenciar conversas)
    $id_conversa = uniqid();

    // Insere a mensagem inicial na tabela "Mensagens"
    $sql_iniciar_mensagem = "INSERT INTO Mensagens (id_remetente, id_destinatario, mensagem, data_envio, id_conversa) 
                             VALUES (:id_remetente, :id_destinatario, 'Conversa iniciada', NOW(), :id_conversa)";
    $stmt_iniciar_mensagem = $conn->prepare($sql_iniciar_mensagem);
    $stmt_iniciar_mensagem->bindParam(':id_remetente', $id_remetente, PDO::PARAM_INT);
    $stmt_iniciar_mensagem->bindParam(':id_destinatario', $id_destinatario, PDO::PARAM_INT);
    $stmt_iniciar_mensagem->bindParam(':id_conversa', $id_conversa, PDO::PARAM_STR); // Usando o id gerado para a conversa
    
    if ($stmt_iniciar_mensagem->execute()) {
        // Redireciona para a página do chat real
        header("Location: conversa.php?id_destinatario=$id_destinatario&id_conversa=$id_conversa");
        exit();
    } else {
        echo "Erro ao iniciar o chat.";
    }
} else {
    echo "Erro: Destinatário não encontrado.";
}
?>
