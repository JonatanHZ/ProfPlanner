<?php
session_start();
include 'conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: agenda-aula.php");
    exit();
}

$agenda_id      = (int) $_POST['agenda_id'];
$professor_id   = (int) $_POST['professor_id'];
$agenda_tipo_id = (int) $_POST['agenda_tipo_id'];
$data           = $_POST['data'];
$hora           = !empty($_POST['hora']) ? $_POST['hora'] : null;
$observacao     = $_POST['observacao'];
$atualizado_por = $_SESSION['usuario_id'];

$stmt = $conn->prepare("
    UPDATE tbAgenda
    SET professor_id   = ?,
        agenda_tipo_id = ?,
        data           = ?,
        hora           = ?,
        observacao     = ?,
        atualizado_por = ?,
        atualizado_em  = NOW()
    WHERE agenda_id = ?
");

$stmt->bind_param("iisssii",
    $professor_id,
    $agenda_tipo_id,
    $data,
    $hora,
    $observacao,
    $atualizado_por,
    $agenda_id
);

if ($stmt->execute()) {
    header("Location: agenda-aula.php");
} else {
    echo "Erro ao atualizar: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>