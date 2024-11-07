<?php
session_start(); // Inicia a sessão

// Verifica se o usuário está logado e determina o tipo
if (!isset($_SESSION['id']) || !isset($_SESSION['tipo'])) {
    header("Location: ../login.php");
    exit();
}

// Recupera o ID e tipo de usuário
$id_usuario = $_SESSION['id'];
$tipo_usuario = $_SESSION['tipo']; // 'aluno' ou 'tutor'

// Define a tabela correta de pesquisa, dependendo do tipo de usuário
$tabela_usuario = ($tipo_usuario === 'aluno') ? 'Tutores' : 'Alunos'; // Aluno procura tutor e tutor procura aluno
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesquisa</title>
    <link rel="stylesheet" href="ASSETS/CSS/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
</head>
<body>
    <!-- Cabeçalho -->
    <div class="header">
        <img src="ASSETS/IMG/capa.png" alt="Imagem de Capa">
    </div>

    <!-- Navegação -->
    <nav class="navbar">
        <a href="index.php">Home</a>
        <a href="sobre_nos.php">Sobre nós</a>
        <a href="dashboard.php">Dashboard</a> <!-- Alterado para uma única dashboard -->
        <a href="logout.php">Logout</a>
    </nav>

    <!-- Pesquisa -->
    <div class="main-content">
        <div class="signup-section">
            <h2>Pesquisar <?php echo $tipo_usuario === 'aluno' ? 'Tutores' : 'Alunos'; ?></h2>
            <form class="signup-form" method="POST" action="proc_pesquisa.php">
                <input type="hidden" name="tipo_usuario" value="<?php echo $tipo_usuario; ?>" />
                <input type="text" id="cidade" name="cidade" placeholder="Cidade..." />
                <br>
                <input type="text" id="estado" name="estado" placeholder="Estado..." />
                <br>
                <input type="text" id="idioma" name="idioma" placeholder="Idioma..." />
                <br>
                <button type="submit" name="pesquisar" class="custom-button">Pesquisar</button>
            </form>
        </div>
    </div>

    <!-- Script para Autocomplete -->
    <script>
        $(document).ready(function() {
            $("#cidade").autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: "autocomplete.php",
                        type: "GET",
                        dataType: "json",
                        data: {
                            term: request.term,
                            tipo: 'cidade'
                        },
                        success: function(data) {
                            response(data);
                        }
                    });
                }
            });

            $("#estado").autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: "autocomplete.php",
                        type: "GET",
                        dataType: "json",
                        data: {
                            term: request.term,
                            tipo: 'estado'
                        },
                        success: function(data) {
                            response(data);
                        }
                    });
                }
            });

            $("#idioma").autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: "autocomplete.php",
                        type: "GET",
                        dataType: "json",
                        data: {
                            term: request.term,
                            tipo: 'idioma'
                        },
                        success: function(data) {
                            response(data);
                        }
                    });
                }
            });
        });
    </script>

    <!-- Rodapé -->
    <div class="footer">
        UNIVESP PI 2024
    </div>
</body>
</html>
