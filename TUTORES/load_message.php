<?php
require 'session_control.php'; // Inclui o controle de sessão
require 'conexao.php';

$id_usuario = $_SESSION['id_tutor'] ?? $_SESSION['id_aluno'];
$tipo_usuario = isset($_SESSION['id_tutor']) ? 'tutor' : 'aluno';

$id_conversor = $_GET['id'] ?? null;
$tipo_conversor = $_GET['tipo_conversor'] ?? null;

if ($id_conversor && $tipo_conversor) {
    // Carrega as mensagens do banco de dados
    $stmt = $conn->prepare("SELECT * FROM Mensagens 
                           WHERE 
                               (id_remetente = ? AND tipo_usuario = ? AND id_destinatario = ? AND tipo_conversor = ?) 
                               OR 
                               (id_remetente = ? AND tipo_usuario = ? AND id_destinatario = ? AND tipo_conversor = ?) 
                           ORDER BY data_envio ASC");
    $stmt->execute([
        $id_usuario, $tipo_usuario, $id_conversor, $tipo_conversor,
        $id_conversor, $tipo_conversor, $id_usuario, $tipo_usuario
    ]);
    
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($messages as $msg) {
        echo '<div>' . htmlspecialchars($msg['mensagem']) . '</div>';
    }
}
?>
