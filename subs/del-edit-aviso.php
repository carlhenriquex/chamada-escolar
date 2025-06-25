<?php
session_start();
include_once("../config/connection.php");

// Função de redirecionamento com mensagem
function redirecionar($mensagem, $tipo)
{
    $_SESSION["mensagem"] = $mensagem;
    $destino = $tipo === 'professor' ? "../dashboard-professor.php" : "../dashboard-gestor.php";
    header("Location: $destino");
    exit;
}


if (!isset($_POST["delete_id"])) {
    redirecionar("ID do aviso não informado.", $_SESSION["tipo"]);
}

$id_aviso = $_POST["delete_id"];
$tipo_usuario = $_SESSION["tipo"];
$id_usuario = $_SESSION["id"];


$sql = "SELECT * FROM avisos WHERE id = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $id_aviso);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    redirecionar("Aviso não encontrado.", $tipo_usuario);
}

$aviso = $result->fetch_assoc();

$autorizado = false;

if ($tipo_usuario === 'gestor') {
    $autorizado = true;
} elseif ($id_usuario == $aviso['autor_id']) {
    $autorizado = true;
}

if (!$autorizado) {
    redirecionar("Você não tem permissão para remover este aviso.", $tipo_usuario);
}

$sql_delete = "DELETE FROM avisos WHERE id = ?";
$stmt_delete = $conexao->prepare($sql_delete);
$stmt_delete->bind_param("i", $id_aviso);

if ($stmt_delete->execute()) {
    redirecionar("Aviso removido com sucesso.", $tipo_usuario);
} else {
    redirecionar("Erro ao remover o aviso.", $tipo_usuario);
}
