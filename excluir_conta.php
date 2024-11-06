<?php
session_start();
require_once './conexao.php';

// Verifica se o usuário está logado e qual tipo de usuário
if (!isset($_SESSION['id']) || !isset($_SESSION['tipo'])) {
    header("Location: ../login.php");
    exit();
}

// Recupera o ID e tipo de usuário
$id_usuario = $_SESSION['id'];
$tipo_usuario = $_SESSION['tipo']; // 'aluno' ou 'tutor'

// Define a tabela correta de usuários com base no tipo de usuário
$tabela_usuario = ($tipo_usuario === 'aluno') ? 'Alunos' : 'Tutores';

// Consulta para buscar a foto de perfil antes de deletar os dados
$sql_foto = "SELECT foto_perfil FROM $tabela_usuario WHERE id = :id";
$stmt_foto = $conn->prepare($sql_foto);
$stmt_foto->bindParam(':id', $id_usuario);
$stmt_foto->execute();
$usuario = $stmt_foto->fetch(PDO::FETCH_ASSOC);

// Deleta o usuário da tabela correspondente
$sql = "DELETE FROM $tabela_usuario WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $id_usuario);

if ($stmt->execute()) {
    // Verifica e deleta a foto de perfil se existir
    if (!empty($usuario['foto_perfil'])) {
        $foto_path = "../uploads/fotos_perfil/" . $usuario['foto_perfil'];
        if (file_exists($foto_path)) {
            unlink($foto_path); // Deleta o arquivo da foto
        }
    }

    // Deleta os idiomas associados ao usuário (se houver)
    $sql_delete_idiomas = "DELETE FROM Idioma WHERE id_usuario = :id_usuario";
    $stmt_delete_idiomas = $conn->prepare($sql_delete_idiomas);
    $stmt_delete_idiomas->bindParam(':id_usuario', $id_usuario);
    $stmt_delete_idiomas->execute();

    // Encerra a sessão e redireciona para a página de login
    session_destroy();
    header("Location: ../login.php");
    exit();
} else {
    // Caso haja erro na exclusão
    die("Erro ao excluir a conta. Tente novamente.");
}
?>
