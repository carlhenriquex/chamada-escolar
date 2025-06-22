<?php
session_start();
include_once("../config/connection.php");

function redirecionar($msg) {
    $_SESSION["mensagem"] = $msg;
    header("Location: ../dashboard-gestor.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirecionar("Requisição inválida.");
}

if (!isset($_POST["id"])) {
    redirecionar("ID do professor não informado.");
}

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
    nome = ?, 
    nascimento = ?, 
    rg = ?, 
    cpf = ?, 
    sexo = ?, 
    raca = ?, 
    sangue = ?, 
    formacao = ?, 
    disciplina = ?, 
    turma = ?, 
    rua = ?, 
    numero = ?, 
    bairro = ?, 
    cidade = ?, 
    complemento = ?, 
    cep = ?, 
    telefone = ?, 
    email = ?
    WHERE id = ?
");

if (!$stmt) {
    redirecionar("Erro ao preparar a consulta: " . $conexao->error);
}

$stmt->bind_param("ssssssssssssssssssi",
    $nome, $nascimento, $rg, $cpf, $sexo, $raca, $sangue,
    $formacao, $disciplina, $turma,
    $rua, $numero, $bairro, $cidade, $complemento, $cep,
    $telefone, $email,
    $id
);

if ($stmt->execute()) {
    redirecionar("Dados do professor atualizados com sucesso.");
} else {
    redirecionar("Erro ao atualizar dados: " . $stmt->error);
}