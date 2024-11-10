<?php
// Conexão com o banco de dados
require_once 'conexao.php';

// Inicialização da sessão
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo_usuario'] !== 'aluno') {
    echo "Usuário não autorizado! Redirecionando para login...";
    header("Location: login.php");
    exit();
}

// Obter todos os tutores disponíveis
$sql_tutores = "SELECT id_usuario, nome, foto_perfil FROM usuarios WHERE tipo_usuario = 'tutor'";
$result_tutores = $conn->query($sql_tutores);

// Processar o agendamento quando o formulário for enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_tutor = $_POST['id_tutor'];
    $data_aula = $_POST['data_aula'];
    $hora_aula = $_POST['hora_aula'];

    // Combina a data e hora
    $data_completa = $data_aula . ' ' . $hora_aula;

    // Inserir o agendamento na tabela de Agendamentos
    $sql_agendamento = "INSERT INTO Agendamentos (id_aluno, id_tutor, data_aula, status) VALUES (:id_aluno, :id_tutor, :data_aula, 'Pendente')";
    $stmt = $conn->prepare($sql_agendamento);
    $stmt->bindParam(':id_aluno', $_SESSION['id_usuario']);
    $stmt->bindParam(':id_tutor', $id_tutor);
    $stmt->bindParam(':data_aula', $data_completa);
    $stmt->execute();

    // Redirecionar para a dashboard após o agendamento
    header("Location: dashboard.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendar Aula</title>
    <link rel="stylesheet" href="ASSETS/CSS/style.css">
</head>
<body>

    <header class="header">
        <img src="ASSETS/IMG/capa.png" alt="Capa do site">
    </header>

    <nav class="navbar">
        <button onclick="window.location.href='index.php'">Home</button>
        <button onclick="window.location.href='dashboard.php'">Dashboard</button>
        <button onclick="window.location.href='logout.php'">Logout</button>
    </nav>

    <main class="main-content">
        <section class="agendar-section">
            <h2>Agendar Aula</h2>
            
            <form action="agendar_aula.php" method="POST">
                <label for="id_tutor">Selecione o Tutor:</label>
                <select name="id_tutor" id="id_tutor" required>
                    <option value="">Escolha um Tutor</option>
                    <?php while ($tutor = $result_tutores->fetch(PDO::FETCH_ASSOC)) { ?>
                        <option value="<?php echo $tutor['id_usuario']; ?>"><?php echo $tutor['nome']; ?></option>
                    <?php } ?>
                </select>
                <br><br>

                <label for="data_aula">Data da Aula:</label>
                <input type="date" name="data_aula" required>
                <br><br>

                <label for="hora_aula">Hora da Aula:</label>
                <input type="time" name="hora_aula" required>
                <br><br>

                <button type="submit">Agendar Aula</button>
            </form>

            <br>
            <button onclick="window.location.href='dashboard.php'">Voltar para a Dashboard</button>
        </section>
    </main>

    <footer class="footer">
        <p>UNIVESP PI 2024</p>
        <p><a href="https://github.com/UNIVESP-PI-BAURU/PI24b.git" target="_blank">https://github.com/UNIVESP-PI-BAURU/PI24b.git</a></p>
    </footer>
    
</body>
</html>
