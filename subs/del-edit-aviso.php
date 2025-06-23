<?php
session_start();
include_once("../config/connection.php");

if (!isset($_POST["delete_id"])) {
    $_SESSION["mensagem"] = "ID do aviso não informado.";
    header("Location: ../dashboard-gestor.php");
    exit;
}

$id_aviso = $_POST["delete_id"];
$tipo_usuario = $_SESSION["tipo"]; // 'gestor' ou 'professor'
$id_usuario = $_SESSION["id"];

// Busca o aviso
$sql = "SELECT * FROM avisos WHERE id = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $id_aviso);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    $_SESSION["mensagem"] = "Aviso não encontrado.";
    header("Location: ../dashboard-gestor.php");
    exit;
}

$aviso = $result->fetch_assoc();

// Verificação de permissão usando ID da sessão
$autorizado = false;

if ($tipo_usuario === 'gestor') {
    $autorizado = true;
} elseif ($id_usuario == $aviso['autor_id']) {
    $autorizado = true;
}

if (!$autorizado) {
    $_SESSION["mensagem"] = "Você não tem permissão para remover este aviso.";
    header("Location: ../dashboard-gestor.php");
    exit;
}

// Exclusão
$sql_delete = "DELETE FROM avisos WHERE id = ?";
$stmt_delete = $conexao->prepare($sql_delete);
$stmt_delete->bind_param("i", $id_aviso);

if ($stmt_delete->execute()) {
    $_SESSION["mensagem"] = "Aviso removido com sucesso.";
} else {
    $_SESSION["mensagem"] = "Erro ao remover o aviso.";
}

header("Location: ../dashboard-gestor.php");
exit;