<?php
session_start();
include_once("../config/connection.php");
include_once("../functions/functionImage.php");

function redirecionar($mensagem, $sucesso = true)
{
    $_SESSION["mensagem"] = $mensagem;
    $_SESSION["tipoMensagem"] = $sucesso ? "sucesso" : "erro";
    header("Location: ../dashboard-gestor.php#tela-02");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirecionar("Requisição inválida.", false);
}

// REMOÇÃO
if (isset($_POST["delete_id"])) {
    $id = intval($_POST["delete_id"]);

    $stmt = $conexao->prepare("UPDATE alunos SET removido_em = NOW() WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        redirecionar("Aluno removido com sucesso.");
    } else {
        redirecionar("Erro ao remover aluno: " . $stmt->error, false);
    }
}

// EDIÇÃO
if (isset($_POST["id"]) && isset($_POST["nome"])) {
    $id            = intval($_POST["id"]);
    $nome          = $_POST["nome"];
    $nascimento    = $_POST["nascimento"];
    $rg            = $_POST["rg"];
    $cpf           = $_POST["cpf"];
    $sexo          = $_POST["sexo"];
    $raca          = $_POST["raca"];
    $sangue        = $_POST["sangue"];
    $nacionalidade = $_POST["nacionalidade"];
    $naturalidade  = $_POST["naturalidade"];
    $turma         = $_POST["turma"];
    $deficiencia   = !empty($_POST["deficiencia"]) ? $_POST["deficiencia"] : null;
    $responsavel_id = !empty($_POST["responsavel_id"]) ? intval($_POST["responsavel_id"]) : null;

    $foto = null;
    if (isset($_FILES["foto"]) && $_FILES["foto"]["error"] !== UPLOAD_ERR_NO_FILE) {
        $foto = processarUploadImagem("foto", "../uploads/alunos/");
        if (!$foto) {
            redirecionar("Erro ao processar a imagem do professor.", false);
        }
    }


    $query = "UPDATE alunos SET 
        nome = ?, nascimento = ?, rg = ?, cpf = ?, sexo = ?, raca = ?, tipo_sanguineo = ?, 
        nacionalidade = ?, naturalidade = ?, turma = ?, deficiencia = ?, responsavel_id = ?";


    if ($novaFoto !== null) {
        $query .= ", foto = ?";
    }

    $query .= " WHERE id = ?";

    $stmt = $conexao->prepare($query);

    if (!$stmt) {
        redirecionar("Erro ao preparar a consulta: " . $conexao->error, false);
    }

    if ($novaFoto !== null) {
        $stmt->bind_param(
            "sssssssssssisi",
            $nome,
            $nascimento,
            $rg,
            $cpf,
            $sexo,
            $raca,
            $sangue,
            $nacionalidade,
            $naturalidade,
            $turma,
            $deficiencia,
            $responsavel_id,
            $novaFoto,
            $id
        );
    } else {
        $stmt->bind_param(
            "sssssssssssii",
            $nome,
            $nascimento,
            $rg,
            $cpf,
            $sexo,
            $raca,
            $sangue,
            $nacionalidade,
            $naturalidade,
            $turma,
            $deficiencia,
            $responsavel_id,
            $id
        );
    }

    if ($stmt->execute()) {
        redirecionar("Dados do aluno atualizados com sucesso.");
    } else {
        redirecionar("Erro ao atualizar dados: " . $stmt->error, false);
    }
}

redirecionar("Ação inválida.", false);
