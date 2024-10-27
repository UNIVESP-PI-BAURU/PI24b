<?php
// Inicia a sessão
session_start();
require_once '../conexao.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['id_aluno']) && !isset($_SESSION['id_tutor'])) {
    header("Location: ../login.php");
    exit();
}

// Identifica o tipo de usuário e obtém o ID
$tipo_usuario = isset($_SESSION['id_aluno']) ? 'aluno' : 'tutor';
$id_usuario = $_SESSION['id_' . $tipo_usuario];

// Carrega os dados do aluno
$sql = "SELECT nome, email, cidade, estado, data_nascimento, biografia 
        FROM Alunos 
        WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $id_usuario);
$stmt->execute();
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

// Carrega os idiomas vinculados ao aluno
$sql_idiomas = "SELECT idioma FROM IdiomaAluno WHERE id_aluno = :id_aluno";
$stmt_idiomas = $conn->prepare($sql_idiomas);
$stmt_idiomas->bindParam(':id_aluno', $id_usuario);
$stmt_idiomas->execute();
$idiomas_aluno = $stmt_idiomas->fetchAll(PDO::FETCH_COLUMN);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil</title>
    <link rel="stylesheet" href="../ASSETS/CSS/style.css">
</head>
<body>

<!-- Formulário para editar o perfil -->
<form action="proc_editar_perfil.php" method="post">
    <input type="hidden" name="id_usuario" value="<?php echo htmlspecialchars($id_usuario); ?>">

    <label for="nome">Nome:</label>
    <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($usuario['nome']); ?>" required>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" required>

    <label for="cidade">Cidade:</label>
    <input type="text" id="cidade" name="cidade" value="<?php echo htmlspecialchars($usuario['cidade']); ?>">

    <label for="estado">Estado:</label>
    <input type="text" id="estado" name="estado" value="<?php echo htmlspecialchars($usuario['estado']); ?>">

    <label for="data_nascimento">Data de Nascimento:</label>
    <input type="date" id="data_nascimento" name="data_nascimento" value="<?php echo htmlspecialchars($usuario['data_nascimento']); ?>">

    <label for="biografia">Biografia:</label>
    <textarea id="biografia" name="biografia"><?php echo htmlspecialchars($usuario['biografia']); ?></textarea>

    <label for="idioma">Idiomas:</label>
    <select id="idioma" name="idioma[]" multiple>
        <option value="Inglês" <?php echo in_array("Inglês", $idiomas_aluno) ? 'selected' : ''; ?>>Inglês</option>
        <option value="Espanhol" <?php echo in_array("Espanhol", $idiomas_aluno) ? 'selected' : ''; ?>>Espanhol</option>
        <option value="Francês" <?php echo in_array("Francês", $idiomas_aluno) ? 'selected' : ''; ?>>Francês</option>
        <option value="Alemão" <?php echo in_array("Alemão", $idiomas_aluno) ? 'selected' : ''; ?>>Alemão</option>
    </select>

    <button type="submit">Salvar</button>
</form>

</body>
</html>
