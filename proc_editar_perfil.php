<?php
// Inicia a sessão e verifica login
session_start();
if (!isset($_SESSION['id']) || !isset($_SESSION['tipo'])) {
    header("Location: login.php");
    exit();
}

// Conexão com o banco de dados
require_once '../conexao.php';
if (!$conn) {
    die("Falha na conexão com o banco de dados.");
}

// Identifica o tipo de usuário e obtém o ID
$tipo_usuario = $_SESSION['tipo']; // 'aluno' ou 'tutor'
$id_usuario = $_SESSION['id']; // ID comum para todos os tipos
$tabela_usuario = ($tipo_usuario === 'aluno') ? 'Alunos' : 'Tutores';

// Recupera os dados do usuário
$sql = "SELECT nome, email, cidade, estado, data_nascimento, biografia, idiomas 
        FROM $tabela_usuario WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $id_usuario);
$stmt->execute();
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

$nome = $usuario['nome'];
$email = $usuario['email'];
$cidade = $usuario['cidade'];
$estado = $usuario['estado'];
$data_nascimento = $usuario['data_nascimento'];
$biografia = $usuario['biografia'];

// Recupera os idiomas do usuário
$sql_idiomas = "SELECT idioma FROM Idioma WHERE id_usuario = :id_usuario";
$stmt_idiomas = $conn->prepare($sql_idiomas);
$stmt_idiomas->bindParam(':id_usuario', $id_usuario);
$stmt_idiomas->execute();
$idiomas = $stmt_idiomas->fetchAll(PDO::FETCH_COLUMN);

// Processa a atualização se for uma requisição POST
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['atualizar'])) {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $cidade = $_POST['cidade'];
    $estado = $_POST['estado'];
    $data_nascimento = $_POST['data_nascimento'];
    $biografia = $_POST['biografia'];
    $idiomas = $_POST['idiomas'];

    // Atualiza a foto de perfil
    if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {
        $foto_temp = $_FILES['foto_perfil']['tmp_name'];
        $foto_nome = basename($_FILES['foto_perfil']['name']);
        $diretorio = "../uploads/fotos_perfil/";

        if (!is_dir($diretorio)) {
            mkdir($diretorio, 0777, true);
        }

        $caminho_foto = $diretorio . $foto_nome;
        if (move_uploaded_file($foto_temp, $caminho_foto)) {
            $sql_foto = "UPDATE $tabela_usuario SET foto_perfil = :foto_perfil WHERE id = :id";
            $stmt_foto = $conn->prepare($sql_foto);
            $stmt_foto->bindParam(':foto_perfil', $caminho_foto);
            $stmt_foto->bindParam(':id', $id_usuario);
            $stmt_foto->execute();
        }
    }

    // Atualiza os outros dados do usuário
    $sql_update = "UPDATE $tabela_usuario SET nome = :nome, email = :email, cidade = :cidade, estado = :estado, 
                   data_nascimento = :data_nascimento, biografia = :biografia WHERE id = :id";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bindParam(':nome', $nome);
    $stmt_update->bindParam(':email', $email);
    $stmt_update->bindParam(':cidade', $cidade);
    $stmt_update->bindParam(':estado', $estado);
    $stmt_update->bindParam(':data_nascimento', $data_nascimento);
    $stmt_update->bindParam(':biografia', $biografia);
    $stmt_update->bindParam(':id', $id_usuario);
    $stmt_update->execute();

    // Atualiza os idiomas do usuário (lógica simples)
    // Limpa os idiomas antigos e insere novos
    $sql_delete_idiomas = "DELETE FROM Idioma WHERE id_usuario = :id_usuario";
    $stmt_delete = $conn->prepare($sql_delete_idiomas);
    $stmt_delete->bindParam(':id_usuario', $id_usuario);
    $stmt_delete->execute();

    foreach ($idiomas as $idioma) {
        if (!empty($idioma)) {
            $sql_insert_idioma = "INSERT INTO Idioma (id_usuario, idioma) VALUES (:id_usuario, :idioma)";
            $stmt_insert = $conn->prepare($sql_insert_idioma);
            $stmt_insert->bindParam(':id_usuario', $id_usuario);
            $stmt_insert->bindParam(':idioma', $idioma);
            $stmt_insert->execute();
        }
    }

    header("Location: perfil.php");
    exit();
}
?>
