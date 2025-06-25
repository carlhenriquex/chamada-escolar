<?php
session_start();
include_once("../config/connection.php");

function redirecionar($mensagem, $sucesso = true)
{
    $_SESSION["mensagem"] = $mensagem;
    $_SESSION["tipoMensagem"] = $sucesso ? "sucesso" : "erro";
    header("Location: ../dashboard-gestor.php#tela-03");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirecionar("Requisição inválida.", false);
}

// REMOÇÃO
if (isset($_POST["delete_id"])) {
    $id = intval($_POST["delete_id"]);

    $stmt = $conexao->prepare("UPDATE professores SET removido_em = NOW() WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        redirecionar("Professor removido com sucesso.");
    } else {
        redirecionar("Erro ao remover professor: " . $stmt->error, false);
    }
}

// EDIÇÃO
if (isset($_POST["id"]) && isset($_POST["nome"])) {

    $id = intval($_POST["id"]);
    $nome = trim($_POST["nome"]);
    $nascimento = $_POST["nascimento"];
    $rg = $_POST["rg"];
    $cpf = $_POST["cpf"];
    $sexo = $_POST["sexo"];
    $raca = $_POST["raca"];
    $sangue = $_POST["sangue"];
    $formacao = $_POST["formacao"];
    $disciplina = $_POST["disciplina"];
    $turma = $_POST["turma"];
    $rua = $_POST["rua"];
    $numero = $_POST["numero"];
    $bairro = $_POST["bairro"];
    $cidade = $_POST["cidade"];
    $complemento = $_POST["complemento"];
    $cep = $_POST["cep"];
    $telefone = $_POST["telefone"];
    $email = $_POST["email"];

    $stmt = $conexao->prepare("UPDATE professores SET 
        nome = ?, nascimento = ?, rg = ?, cpf = ?, sexo = ?, raca = ?, tipo_sanguineo = ?, 
        formacao = ?, disciplina = ?, turma = ?, rua = ?, numero = ?, bairro = ?, cidade = ?, 
        complemento = ?, cep = ?, telefone = ?, email = ?
        WHERE id = ?
    ");

    if (!$stmt) {
        redirecionar("Erro ao preparar a consulta: " . $conexao->error, false);
    }

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

    if ($stmt->execute()) {
        redirecionar("Dados do professor atualizados com sucesso.");
    } else {
        redirecionar("Erro ao atualizar dados: " . $stmt->error, false);
    }
}

redirecionar("Ação inválida.", false);
