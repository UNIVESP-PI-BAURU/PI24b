<?php
// Inclui o arquivo de conexão com o banco de dados
require_once 'conexao.php';

// Inicia a sessão
session_start();

// Verifica se foi enviado um formulário de cadastro
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["registrar"])) {
    // Recupera os dados do formulário
    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $senha = password_hash($_POST["senha"], PASSWORD_DEFAULT); // Criptografa a senha
    $cidade = $_POST["cidade"];
    $estado = $_POST["estado"];
    $data_nascimento = $_POST["data_nascimento"];
    $biografia = $_POST["biografia"];
    $idiomas = $_POST["idiomas"]; // Array de idiomas
    $tipo_usuario = $_POST["tipo_usuario"]; // Indica se é aluno ou tutor

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
            echo "O arquivo não é uma imagem.";
            $uploadOk = 0;
        }

        // Verifica se o arquivo já existe
        if (file_exists($target_file)) {
            echo "Desculpe, o arquivo já existe.";
            $uploadOk = 0;
        }

        // Verifica o tamanho do arquivo
        if ($_FILES["foto_perfil"]["size"] > 500000) { // 500KB
            echo "Desculpe, o arquivo é muito grande.";
            $uploadOk = 0;
        }

        // Permite apenas certos formatos de imagem
        if (!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
            echo "Desculpe, apenas arquivos JPG, JPEG, PNG e GIF são permitidos.";
            $uploadOk = 0;
        }

        // Verifica se tudo está ok para fazer o upload
        if ($uploadOk == 1) {
            if (move_uploaded_file($_FILES["foto_perfil"]["tmp_name"], $target_file)) {
                $foto_perfil = $target_file; // Salva o caminho da imagem
            } else {
                echo "Desculpe, ocorreu um erro ao enviar o arquivo.";
            }
        }
    }

    // Inserindo dados na tabela correta (Alunos ou Tutores)
    try {
        if ($tipo_usuario === 'aluno') {
            $stmt = $conn->prepare("INSERT INTO Alunos (nome, email, senha, cidade, estado, data_nascimento, biografia, foto_perfil) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        } else {
            $stmt = $conn->prepare("INSERT INTO Tutores (nome, email, senha, cidade, estado, data_nascimento, biografia, foto_perfil) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        }

        // Executa a inserção dos dados
        $stmt->execute([$nome, $email, $senha, $cidade, $estado, $data_nascimento, $biografia, $foto_perfil]);

        // Insere os idiomas
        $id_usuario = $conn->lastInsertId();
        foreach ($idiomas as $idioma) {
            if ($tipo_usuario === 'aluno') {
                $stmt = $conn->prepare("INSERT INTO IdiomaAluno (id_aluno, idioma) VALUES (?, ?)");
            } else {
                $stmt = $conn->prepare("INSERT INTO IdiomaTutor (id_tutor, idioma) VALUES (?, ?)");
            }
            $stmt->execute([$id_usuario, $idioma]);
        }

        // Mensagem de sucesso na sessão
        $_SESSION['message'] = 'Cadastro realizado com sucesso!';

        // Redireciona para a página de login
        header("Location: login.php"); // Altere para login.php se você renomeou a página
        exit(); // Para garantir que o script pare aqui
    } catch (PDOException $e) {
        echo "Erro ao cadastrar: " . $e->getMessage();
    }
}
?>
