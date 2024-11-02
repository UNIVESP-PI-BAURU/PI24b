<?php
// Inclui o arquivo de conexão com o banco de dados
require_once 'conexao.php';

// Verifica se foi enviado um formulário de cadastro
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["registrar"])) {
    // Recupera os dados do formulário com limpeza
    $nome = htmlspecialchars(trim($_POST["nome"]));
    $email = htmlspecialchars(trim($_POST["email"]));
    $senha = password_hash(trim($_POST["senha"]), PASSWORD_DEFAULT);
    $cidade = htmlspecialchars(trim($_POST["cidade"]));
    $estado = htmlspecialchars(trim($_POST["estado"]));
    $data_nascimento = !empty($_POST["data_nascimento"]) ? htmlspecialchars(trim($_POST["data_nascimento"])) : null;
    $biografia = htmlspecialchars(trim($_POST["biografia"]));
    $tipo_usuario = isset($_POST["tipo_usuario"]) ? $_POST["tipo_usuario"] : null; // Altere para capturar apenas um tipo

    // Verifica se uma imagem foi enviada e define o caminho
    $foto_perfil = null;
    if (isset($_FILES["foto_perfil"]) && $_FILES["foto_perfil"]["error"] == UPLOAD_ERR_OK) {
        $target_dir = "ASSETS/IMG/USUARIOS/";
        $target_file = $target_dir . basename($_FILES["foto_perfil"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Verifica se o arquivo é uma imagem
        $check = getimagesize($_FILES["foto_perfil"]["tmp_name"]);
        if ($check === false) {
            $_SESSION['error'] = "O arquivo não é uma imagem.";
            $uploadOk = 0;
        }

        // Verifica se o arquivo já existe
        if (file_exists($target_file)) {
            $_SESSION['error'] = "Desculpe, o arquivo já existe.";
            $uploadOk = 0;
        }

        // Verifica o tamanho do arquivo
        if ($_FILES["foto_perfil"]["size"] > 5000000) {
            $_SESSION['error'] = "Desculpe, o arquivo é muito grande.";
            $uploadOk = 0;
        }

        // Permite apenas certos formatos de imagem
        if (!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
            $_SESSION['error'] = "Desculpe, apenas arquivos JPG, JPEG, PNG e GIF são permitidos.";
            $uploadOk = 0;
        }

        // Verifica se tudo está ok para fazer o upload
        if ($uploadOk == 1) {
            if (move_uploaded_file($_FILES["foto_perfil"]["tmp_name"], $target_file)) {
                $foto_perfil = $target_file;
            } else {
                $_SESSION['error'] = "Desculpe, ocorreu um erro ao enviar o arquivo.";
            }
        }
    }

    // Inserindo dados na tabela correta
    try {
        if ($tipo_usuario === 'aluno') {
            $stmt = $conn->prepare(
                "INSERT INTO Alunos (nome, email, senha, cidade, estado, data_nascimento, biografia, foto_perfil) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
            );
        } elseif ($tipo_usuario === 'tutor') {
            $stmt = $conn->prepare(
                "INSERT INTO Tutores (nome, email, senha, cidade, estado, data_nascimento, biografia, foto_perfil) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
            );
        }

        if (isset($stmt)) {
            $stmt->bind_param("ssssssss", $nome, $email, $senha, $cidade, $estado, $data_nascimento, $biografia, $foto_perfil);
            $stmt->execute();
        }

        $_SESSION['message'] = "Cadastro realizado com sucesso!";
        header("Location: cadastro.php");
        exit;
    } catch (Exception $e) {
        $_SESSION['error'] = "Erro ao cadastrar: " . $e->getMessage();
        header("Location: cadastro.php");
        exit;
    }
}

// Se a requisição não for POST, redireciona para a página de cadastro
header("Location: cadastro.php");
exit;
?>
