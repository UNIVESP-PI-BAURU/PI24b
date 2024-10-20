<?php
// Inicia a sessão
session_start();
require_once '../conexao.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['id_aluno']) && !isset($_SESSION['id_tutor'])) {
    header("Location: ../login.php");
    exit();
}

// Lógica para buscar os dados do usuário, semelhante ao perfil
$tipo_usuario = isset($_SESSION['id_aluno']) ? 'aluno' : 'tutor';
$id_usuario = $_SESSION['id_' . $tipo_usuario];
$tabela_usuario = ($tipo_usuario === 'aluno') ? 'Alunos' : 'Tutores';

$sql = "SELECT nome, email, cidade, estado, data_nascimento, biografia, idiomas 
        FROM $tabela_usuario 
        WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $id_usuario);
$stmt->execute();
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);
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

    <button type="submit">Salvar</button>
</form>

</body>
</html>
