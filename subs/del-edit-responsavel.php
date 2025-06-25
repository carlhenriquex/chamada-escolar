<?php
session_start();
include_once("../config/connection.php");

// Função para redirecionar com mensagem e tipo
function redirecionar($mensagem, $sucesso = true) {
    $_SESSION["mensagem"] = $mensagem;
    $_SESSION["tipo_msg"] = $sucesso ? "sucesso" : "erro";
    header("Location: ../dashboard-gestor.php#tela-04");
    exit;
}

// REMOÇÃO
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete_id"])) {
    $id = intval($_POST["delete_id"]);
    $query = "UPDATE responsaveis SET removido_em = NOW() WHERE id = ?";
    $stmt = $conexao->prepare($query);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        redirecionar("Responsável removido com sucesso.");
    } else {
        redirecionar("Erro ao remover responsável: " . $stmt->error, false);
    }
}

// EDIÇÃO
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id"])) {
    $id = intval($_POST["id"]);

    $nome = $_POST["nome"];
    $rg = $_POST["rg"];
    $cpf = $_POST["cpf"];
    $parentesco = $_POST["parentesco"];
    $rua = $_POST["rua"];
    $numero = $_POST["numero"];
    $bairro = $_POST["bairro"];
    $cidade = $_POST["cidade"];
    $complemento = $_POST["complemento"];
    $cep = $_POST["cep"];
    $telefone = $_POST["telefone"];
    $email = $_POST["email"];

    $query = "UPDATE responsaveis SET 
        nome=?, rg=?, cpf=?, parentesco=?, rua=?, numero=?, bairro=?, cidade=?, complemento=?, cep=?, telefone=?, email=?
        WHERE id=?";

    $stmt = $conexao->prepare($query);
    $stmt->bind_param(
        "ssssssssssssi",
        $nome, $rg, $cpf, $parentesco, $rua, $numero, $bairro, $cidade, $complemento, $cep, $telefone, $email, $id
    );

    if ($stmt->execute()) {
        redirecionar("Responsável atualizado com sucesso.");
    } else {
        redirecionar("Erro ao atualizar responsável: " . $stmt->error, false);
    }
}

redirecionar("Ação inválida.", false);
