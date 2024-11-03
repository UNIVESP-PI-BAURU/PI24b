<?php
session_start();
if (!isset($_SESSION['id_aluno'])) {
    error_log("Aluno não logado, redirecionando para login.");
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesquisa de Alunos</title>
    <link rel="stylesheet" href="ASSETS/CSS/style.css">
    <script src="https://code.jquery.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
</head>

<body>

    <!-- Cabeçalho -->
    <div class="header">
        <img src="ASSETS/IMG/capa.png" alt="Imagem de Capa">
    </div>

    <!-- Navegação -->
    <nav class="navbar">
        <a href="../index.php">Home</a>
        <a href="../sobre_nos.php">Sobre nós</a>
        <a href="<?php echo isset($_SESSION['id_tutor']) ? './dashboard_tutor.php' : './dashboard_aluno.php'; ?>">Dashboard</a>
        <a href="../logout.php">Logout</a>
    </nav>

    <!-- Pesquisa de Alunos -->
    <div class="main-content">
        <div class="signup-section">
            <h2>Pesquisar Alunos</h2>
            <form class="signup-form" method="POST" action="proc_pesquisa_alunos.php">
                <input type="hidden" name="id_aluno" value="<?php echo htmlspecialchars($_SESSION['id_aluno']); ?>" /> <!-- Campo oculto para ID do aluno -->
                <input type="hidden" name="tipo_usuario" value="aluno" /> <!-- Tipo de usuário -->
                <input type="hidden" name="tipo_conversor" value="aluno" /> <!-- Tipo de conversor -->
                <input type="text" id="cidade" name="cidade" placeholder="Cidade..." />
                <br>
                <input type="text" id="estado" name="estado" placeholder="Estado..." />
                <br>
                <input type="text" id="idioma" name="idioma" placeholder="Idioma..." />
                <br>
                <button type="submit" name="pesquisar" class="custom-button">
                    Pesquisar
                </button>
            </form>
        </div>
    </div>

    <!-- Script para Autocomplete -->
    <script>
        $(document).ready(function() {
            $("#cidade").autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: "autocomplete_alunos.php",
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
                        url: "autocomplete_alunos.php",
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
                        url: "autocomplete_alunos.php",
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
