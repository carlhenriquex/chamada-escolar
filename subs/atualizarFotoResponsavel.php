<?php
session_start();
include_once("../config/connection.php");
include_once("../functions/functionImage.php");

function redirecionar($mensagem, $sucesso = true)
{
    $_SESSION["mensagem"] = $mensagem;
    $_SESSION["tipoMensagem"] = $sucesso ? "sucesso" : "erro";
    header("Location: ../perfil-responsavel.php");
    exit;
}

// Verifica se o usuário está logado e é um responsável
if (!isset($_SESSION["id"]) || $_SESSION["tipo"] !== "responsavel") {
    redirecionar("Acesso negado. Faça login como responsável.", false);
}

$responsavel_id = $_SESSION["id"];

// Verifica se o arquivo foi enviado corretamente
if (!isset($_FILES["nova_foto"]) || $_FILES["nova_foto"]["error"] !== UPLOAD_ERR_OK) {
    redirecionar("Erro ao enviar o arquivo.", false);
}

// Processa a imagem (usando o ID como nome fixo, com extensão .jpg)
$novoNomeArquivo = processarUploadImagem("nova_foto", "../uploads/responsaveis/", $responsavel_id . ".jpg");

if (!$novoNomeArquivo) {
    redirecionar("Erro ao processar a imagem. Verifique o formato e tamanho do arquivo.", false);
}

// Atualiza o nome da imagem no banco
$fotoNome = basename($novoNomeArquivo);
$stmt = $conexao->prepare("UPDATE responsaveis SET foto = ? WHERE id = ?");
$stmt->bind_param("si", $fotoNome, $responsavel_id);

if ($stmt->execute()) {
    redirecionar("Foto de perfil atualizada com sucesso!");
} else {
    redirecionar("Erro ao atualizar a foto no banco de dados: " . $stmt->error, false);
}
