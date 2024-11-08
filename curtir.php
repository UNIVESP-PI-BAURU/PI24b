<?php
session_start();
require_once 'conexao.php'; // Conexão com o banco de dados

// Verifica se o aluno está logado
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo_usuario'] != 'aluno') {
    echo 'erro'; // Não pode curtir se não for aluno
    exit();
}

if (isset($_POST['id_tutor'])) {
    $id_aluno = $_SESSION['id_usuario']; // ID do aluno logado
    $id_tutor = $_POST['id_tutor']; // ID do tutor que está sendo curtido

    // Verifica se o aluno já curtiu o tutor
    $sql_check = "SELECT * FROM Curtidas WHERE id_aluno = :id_aluno AND id_tutor = :id_tutor";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bindParam(':id_aluno', $id_aluno);
    $stmt_check->bindParam(':id_tutor', $id_tutor);
    $stmt_check->execute();

    if ($stmt_check->rowCount() > 0) {
        echo 'erro'; // O aluno já curtiu o tutor
    } else {
        // Insere a nova curtida
        $sql_insert = "INSERT INTO Curtidas (id_aluno, id_tutor) VALUES (:id_aluno, :id_tutor)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bindParam(':id_aluno', $id_aluno);
        $stmt_insert->bindParam(':id_tutor', $id_tutor);

        if ($stmt_insert->execute()) {
            echo 'sucesso'; // Curtida registrada com sucesso
        } else {
            echo 'erro'; // Erro ao tentar inserir
        }
    }
} else {
    echo 'erro'; // Parâmetro faltando
}
?>
