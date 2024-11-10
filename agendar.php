<?php
session_start();
require_once 'conexao.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['id_usuario']) || !isset($_SESSION['tipo_usuario'])) {
    header("Location: login.php");
    exit();
}

// Obtém o ID do tutor a partir da URL
$id_tutor = isset($_GET['id']) ? intval($_GET['id']) : null;
if (!$id_tutor) {
    header("Location: dashboard.php"); // Redireciona se o ID não for válido
    exit();
}

// Verifica se o usuário logado é um aluno
if ($_SESSION['tipo_usuario'] !== 'aluno') {
    header("Location: dashboard.php");
    exit();
}

// Consulta as disponibilidades do tutor
$sql = "SELECT id, dia_semana, hora_inicio, hora_fim FROM Disponibilidade_Tutores WHERE id_tutor = :id_tutor";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id_tutor', $id_tutor, PDO::PARAM_INT);
$stmt->execute();
$disponibilidade_tutor = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Lidar com o agendamento
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_aluno = $_SESSION['id_usuario'];
    $dia_semana = $_POST['dia_semana'];
    $hora_aula = $_POST['hora_aula'];

    // Encontrar a data e hora correspondente
    $data_aula = $_POST['data_aula'] . ' ' . $hora_aula;

    // Insere o agendamento na tabela de Agendamentos
    $sql_agendamento = "INSERT INTO Agendamentos (id_aluno, id_tutor, data_aula, status) VALUES (:id_aluno, :id_tutor, :data_aula, 'Pendente')";
    $stmt_agendamento = $conn->prepare($sql_agendamento);
    $stmt_agendamento->bindParam(':id_aluno', $id_aluno, PDO::PARAM_INT);
    $stmt_agendamento->bindParam(':id_tutor', $id_tutor, PDO::PARAM_INT);
    $stmt_agendamento->bindParam(':data_aula', $data_aula, PDO::PARAM_STR);
    $stmt_agendamento->execute();

    header("Location: dashboard.php"); // Redireciona após agendamento
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
    <script>
        // Função para exibir horários disponíveis com base no dia escolhido
        function exibirHorarios() {
            const diaSemana = document.getElementById('dia_semana').value;
            const horarios = <?php echo json_encode($disponibilidade_tutor); ?>;
            const selectHorario = document.getElementById('hora_aula');
            
            // Limpa as opções de horário anteriores
            selectHorario.innerHTML = '';
            
            // Filtra os horários com base no dia escolhido
            const horariosDisponiveis = horarios.filter(item => item.dia_semana === diaSemana);

            // Se houver horários, exibe as opções
            if (horariosDisponiveis.length > 0) {
                horariosDisponiveis.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.hora_inicio;
                    option.textContent = item.hora_inicio + ' às ' + item.hora_fim;
                    selectHorario.appendChild(option);
                });
            } else {
                const option = document.createElement('option');
                option.value = '';
                option.textContent = 'Não há horários disponíveis neste dia';
                selectHorario.appendChild(option);
            }
        }

        // Função para validar a data selecionada
        function validarData() {
            const dataSelecionada = new Date(document.getElementById('data_aula').value);
            const dataAtual = new Date();
            
            // Verifica se a data escolhida é no futuro
            if (dataSelecionada < dataAtual) {
                alert('Você não pode agendar uma aula no passado.');
                return false;
            }
            return true;
        }
    </script>
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
            <h2>Agendar Aula com o Tutor</h2>
            <h3>Disponibilidade do Tutor</h3>

            <form action="agendar.php?id=<?php echo $id_tutor; ?>" method="POST" onsubmit="return validarData()">
                <label for="dia_semana">Escolha o Dia:</label>
                <select name="dia_semana" id="dia_semana" onchange="exibirHorarios()">
                    <option value="">Selecione um Dia</option>
                    <option value="Segunda">Segunda</option>
                    <option value="Terça">Terça</option>
                    <option value="Quarta">Quarta</option>
                    <option value="Quinta">Quinta</option>
                    <option value="Sexta">Sexta</option>
                    <option value="Sábado">Sábado</option>
                    <option value="Domingo">Domingo</option>
                </select>
                <br><br>

                <label for="data_aula">Escolha a data:</label>
                <input type="date" name="data_aula" required>
                <br><br>

                <label for="hora_aula">Escolha a hora:</label>
                <select name="hora_aula" id="hora_aula">
                    <option value="">Primeiro, escolha o dia</option>
                </select>

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
