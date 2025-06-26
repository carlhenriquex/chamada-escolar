<?php
session_start();
include_once("../config/connection.php");
include_once("../functions/functionImage.php");

function redirecionar($mensagem, $sucesso = true)
{
    $_SESSION["mensagem"] = $mensagem;
    $_SESSION["tipoMensagem"] = $sucesso ? "sucesso" : "erro";
    header("Location: ../dashboard-gestor.php#tela-03");
    exit;
}

// Valida requisição
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirecionar("Requisição inválida.", false);
}

//  REMOÇÃO DE PROFESSOR 
if (!empty($_POST["delete_id"])) {
    $id = intval($_POST["delete_id"]);

    $stmt = $conexao->prepare("UPDATE professores SET removido_em = NOW() WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        redirecionar("Professor removido com sucesso.");
    } else {
        redirecionar("Erro ao remover professor: " . $stmt->error, false);
    }
}

//  EDIÇÃO DE PROFESSOR 
if (!empty($_POST["id"]) && !empty($_POST["nome"])) {

    $id           = intval($_POST["id"]);
    $nome         = trim($_POST["nome"]);
    $nascimento   = $_POST["nascimento"];
    $rg           = $_POST["rg"];
    $cpf          = $_POST["cpf"];
    $sexo         = $_POST["sexo"];
    $raca         = $_POST["raca"];
    $sangue       = $_POST["sangue"];
    $formacao     = $_POST["formacao"];
    $disciplina   = $_POST["disciplina"];
    $turma        = $_POST["turma"];
    $rua          = $_POST["rua"];
    $numero       = $_POST["numero"];
    $bairro       = $_POST["bairro"];
    $cidade       = $_POST["cidade"];
    $complemento  = $_POST["complemento"];
    $cep          = $_POST["cep"];
    $telefone     = $_POST["telefone"];
    $email        = $_POST["email"];


    $foto = null;
    if (isset($_FILES["foto"]) && $_FILES["foto"]["error"] !== UPLOAD_ERR_NO_FILE) {
        $foto = processarUploadImagem($_FILES["foto"], "../uploads/professores/");
        if (!$foto) {
            redirecionar("Erro ao processar a imagem do professor.", false);
        }
    }

    $sql = "
        UPDATE professores SET 
            nome = ?, nascimento = ?, rg = ?, cpf = ?, sexo = ?, raca = ?, tipo_sanguineo = ?, 
            formacao = ?, disciplina = ?, turma = ?, rua = ?, numero = ?, bairro = ?, cidade = ?, 
            complemento = ?, cep = ?, telefone = ?, email = ?";

    if ($foto) {
        $sql .= ", foto = ?";
    }

    $sql .= " WHERE id = ?";

    $stmt = $conexao->prepare($sql);

    if (!$stmt) {
        redirecionar("Erro ao preparar a consulta: " . $conexao->error, false);
    }

    if ($foto) {
        $stmt->bind_param(
            "sssssssssssssssssssi",
            $nome,
            $nascimento,
            $rg,
            $cpf,
            $sexo,
            $raca,
            $sangue,
            $formacao,
            $disciplina,
            $turma,
            $rua,
            $numero,
            $bairro,
            $cidade,
            $complemento,
            $cep,
            $telefone,
            $email,
            $foto,
            $id
        );
    } else {
        $stmt->bind_param(
            "ssssssssssssssssssi",
            $nome,
            $nascimento,
            $rg,
            $cpf,
            $sexo,
            $raca,
            $sangue,
            $formacao,
            $disciplina,
            $turma,
            $rua,
            $numero,
            $bairro,
            $cidade,
            $complemento,
            $cep,
            $telefone,
            $email,
            $id
        );
    }

    if ($stmt->execute()) {
        redirecionar("Dados do professor atualizados com sucesso.");
    } else {
        redirecionar("Erro ao atualizar dados: " . $stmt->error, false);
    }
}

redirecionar("Ação inválida.", false);
