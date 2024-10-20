<?php
// Incluir conexão com o banco de dados
require_once '../conexao.php';

// Determina o tipo de usuário e busca os dados
$tipo_usuario = isset($_SESSION['id_aluno']) ? 'aluno' : 'tutor';
$id_usuario = $_SESSION['id_' . $tipo_usuario];
$tabela_usuario = ($tipo_usuario === 'aluno') ? 'Alunos' : 'Tutores';

// Consulta SQL para buscar todos os dados do usuário
$sql = "SELECT nome, email, foto_perfil, cidade, estado, data_nascimento, biografia, idiomas 
        FROM $tabela_usuario 
        WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $id_usuario);
$stmt->execute();
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

// Verifica se os dados foram encontrados
if (!$usuario) {
    echo "<p>Usuário não encontrado.</p>";
    exit();
}

// Transforma o campo de idiomas (armazenado como JSON ou separado por vírgulas) em um array para exibição
$idiomas = explode(',', $usuario['idiomas']);
?>

<!-- Exibição do perfil -->
<h1>Perfil de <?php echo ($tipo_usuario === 'tutor' ? "Tutor(a)" : "Aluno(a)"); ?>: <?php echo htmlspecialchars($usuario['nome']); ?></h1>

<div class="foto-perfil">
    <?php if (!empty($usuario['foto_perfil'])): ?>
        <img src="<?php echo htmlspecialchars($usuario['foto_perfil']); ?>" alt="Avatar" style="width: 150px; height: 150px; border-radius: 50%;">
    <?php else: ?>
        <p>Sem foto</p>
    <?php endif; ?>
</div>

<div class="info-usuario">
    <p><strong>Email:</strong> <?php echo htmlspecialchars($usuario['email']); ?></p>
    <p><strong>Cidade/Estado:</strong> 
        <?php echo htmlspecialchars($usuario['cidade']); ?>, <?php echo htmlspecialchars($usuario['estado']); ?>
    </p>
    <p><strong>Data de Nascimento:</strong> <?php echo htmlspecialchars($usuario['data_nascimento']); ?></p>
    <p><strong>Idiomas:</strong> 
        <?php echo implode(', ', array_map('htmlspecialchars', $idiomas)); ?>
    </p>
    <p><strong>Sobre:</strong> <?php echo htmlspecialchars($usuario['biografia']); ?></p>
</div>
