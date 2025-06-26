<?php
session_start();
include_once("../config/connection.php");

// Função de redirecionamento com mensagem estilizada
function redirecionar($mensagem, $tipo, $sucesso = true)
{
    $_SESSION["mensagem"] = $mensagem;
    $_SESSION["tipoMensagem"] = $sucesso ? "sucesso" : "erro";

    $destino = $tipo === 'professor' ? "../dashboard-professor.php#tela-01" : "../dashboard-gestor.php#tela-01";
    header("Location: $destino");
    exit;
}

if (!isset($_POST["delete_id"])) {
    redirecionar("ID do aviso não informado.", $_SESSION["tipo"], false);
}

$id_aviso = $_POST["delete_id"];
$tipo_usuario = $_SESSION["tipo"];
$id_usuario = $_SESSION["id"];

// Verifica se o aviso existe
$sql = "SELECT * FROM avisos WHERE id = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $id_aviso);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    redirecionar("Aviso não encontrado.", $tipo_usuario, false);
}

$aviso = $result->fetch_assoc();

// Verifica permissão para excluir (deve bater tipo e id)
$autorizado = (
    $tipo_usuario === 'gestor' || 
    ($aviso['autor_tipo'] === $tipo_usuario && $aviso['autor_id'] == $id_usuario)
);

if (!$autorizado) {
    redirecionar("Você não tem permissão para remover este aviso.", $tipo_usuario, false);
}

// Executa a exclusão
$sql_delete = "DELETE FROM avisos WHERE id = ?";
$stmt_delete = $conexao->prepare($sql_delete);
$stmt_delete->bind_param("i", $id_aviso);

if ($stmt_delete->execute()) {
    redirecionar("Aviso removido com sucesso.", $tipo_usuario);
} else {
    redirecionar("Erro ao remover o aviso.", $tipo_usuario, false);
}