<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <link rel="stylesheet" href="style.css"> <!-- Seu CSS se necessário -->
</head>
<body>

<h2>Cadastro de Usuário</h2>

<?php
if (isset($_SESSION['error'])) {
    echo "<p style='color:red;'>" . $_SESSION['error'] . "</p>";
    unset($_SESSION['error']);
}
if (isset($_GET['success'])) {
    echo "<p style='color:green;'>" . $_GET['success'] . "</p>";
}
?>

<form action="proc_cadastro.php" method="POST">
    <!-- Campo de Email -->
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>

    <!-- Campo de Senha -->
    <label for="senha">Senha:</label>
    <input type="password" id="senha" name="senha" required>

    <!-- Campo de Nome -->
    <label for="nome">Nome:</label>
    <input type="text" id="nome" name="nome" required>

    <!-- Campo de Tipo de Usuário (Aluno ou Tutor) -->
    <label for="tipo_usuario">Tipo de Usuário:</label>
    <select id="tipo_usuario" name="tipo_usuario" required>
        <option value="aluno">Aluno</option>
        <option value="tutor">Tutor</option>
    </select>

    <!-- Campo de Idioma -->
    <label for="idioma1">Idioma:</label>
    <input type="text" id="idioma1" name="idiomas[]" required>

    <!-- Botão para adicionar mais campos de idioma -->
    <button type="button" id="addIdioma">Adicionar Idioma</button>

    <!-- Botão de submit -->
    <button type="submit">Cadastrar</button>
</form>

<script>
// JavaScript para adicionar campos de idiomas dinâmicos
document.getElementById('addIdioma').addEventListener('click', function() {
    // Cria um novo campo de idioma
    const newInput = document.createElement('input');
    newInput.type = 'text';
    newInput.name = 'idiomas[]';  // Para enviar como array no PHP
    newInput.placeholder = 'Idioma adicional';

    // Insere o novo campo logo abaixo do último campo de idioma
    document.querySelector('form').insertBefore(newInput, this);
});
</script>

</body>
</html>
