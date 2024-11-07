<?php
// Inicia a sessão e verifica login
session_start();
if (!isset($_SESSION['id']) || !isset($_SESSION['tipo'])) {
    header("Location: login.php");
    exit();
}

// Inclui a conexão com o banco
require_once 'conexao.php';

// Determina o tipo de usuário e busca os dados
$tipo_usuario = $_SESSION['tipo']; // 'aluno' ou 'tutor'
$id_usuario = $_SESSION['id']; // ID comum para todos os tipos
$tabela_usuario = ($tipo_usuario === 'aluno') ? 'Alunos' : 'Tutores';

// Debug: Exibindo tipo de usuário e tabela
echo "Tipo de usuário: $tipo_usuario<br>";  // Debug
echo "Tabela associada ao usuário: $tabela_usuario<br>";  // Debug

// Inicializa as variáveis
$nome = $_POST['nome'];
$email = $_POST['email'];
$cidade = $_POST['cidade'];
$estado = $_POST['estado'];
$data_nascimento = $_POST['data_nascimento'];
$biografia = $_POST['biografia'];
$idioma = $_POST['idioma']; // Alterado de 'idiomas' para 'idioma'

// Debug: Exibindo valores das variáveis
echo "Nome: $nome<br>";  // Debug
echo "Email: $email<br>";  // Debug
echo "Cidade: $cidade<br>";  // Debug
echo "Estado: $estado<br>";  // Debug
echo "Data de Nascimento: $data_nascimento<br>";  // Debug
echo "Biografia: $biografia<br>";  // Debug
echo "Idioma: $idioma<br>";  // Debug

// Manipulação da foto de perfil
// Buscando foto atual do banco de dados
$sql = "SELECT foto_perfil FROM $tabela_usuario WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $id_usuario, PDO::PARAM_INT);
$stmt->execute();
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

// Debug: Exibindo foto atual
echo "Foto atual: " . $usuario['foto_perfil'] . "<br>";  // Debug

// Foto atual
$foto_perfil = $usuario['foto_perfil']; // Foto atual
if (!empty($_FILES['foto_perfil']['name'])) {
    // Novo arquivo enviado
    $target_dir = "../uploads/fotos_perfil/";
    $foto_perfil = basename($_FILES["foto_perfil"]["name"]);
    $target_file = $target_dir . $foto_perfil;

    // Debug: Exibindo caminho do arquivo
    echo "Caminho do arquivo: $target_file<br>";  // Debug

    if (move_uploaded_file($_FILES["foto_perfil"]["tmp_name"], $target_file)) {
        // Foto movida com sucesso
        echo "Foto movida com sucesso.<br>";  // Debug
    } else {
        die("Erro ao enviar a foto.");
    }
}

// Consulta SQL para atualizar os dados do usuário
$sql = "UPDATE $tabela_usuario SET nome = :nome, email = :email, cidade = :cidade, estado = :estado, 
        data_nascimento = :data_nascimento, biografia = :biografia, idioma = :idioma, foto_perfil = :foto_perfil 
        WHERE id = :id";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':nome', $nome);
$stmt->bindParam(':email', $email);
$stmt->bindParam(':cidade', $cidade);
$stmt->bindParam(':estado', $estado);
$stmt->bindParam(':data_nascimento', $data_nascimento);
$stmt->bindParam(':biografia', $biografia);
$stmt->bindParam(':idioma', $idioma); // Alterado para 'idioma'
$stmt->bindParam(':foto_perfil', $foto_perfil);
$stmt->bindParam(':id', $id_usuario);

// Debug: Exibindo a consulta SQL antes de executar
echo "Consulta SQL: $sql<br>";  // Debug

if ($stmt->execute()) {
    echo "Perfil atualizado com sucesso.<br>";  // Debug
    header("Location: perfil.php");
    exit();
} else {
    die("Erro ao atualizar o perfil.");
}
?>
