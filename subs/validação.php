<?php
session_start();

function redirecionar($msg, $sucesso = false) {
    $_SESSION["msg"] = $msg;
    $_SESSION["tipoMensagem"] = $sucesso ? "sucesso" : "erro";
    header("Location: ../login.php");
    exit;
}

if (
    !isset($_POST['submit']) ||
    empty($_POST['email']) || 
    empty($_POST['senha']) || 
    empty($_POST['tipo'])
) {
    redirecionar("Preencha todos os campos e selecione um tipo de usuário.", false);
}

include("../config/connection.php");

$email = $_POST['email'];
$password = $_POST['senha'];
$tipo = $_POST['tipo'];

switch ($tipo) {
    case 'gestor':
        $table = "gestores";
        break;
    case 'professor':
        $table = "professores";
        break;
    case 'responsavel':
        $table = "responsaveis";
        break;
    default:
        redirecionar("Tipo de usuário inválido.", false);
}

$stmt = $conexao->prepare("SELECT * FROM $table WHERE email = ? AND removido_em IS NULL");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    redirecionar("E-mail não encontrado.", false);
}

$usuario = $result->fetch_assoc();

if (!password_verify($password, $usuario["senha"])) {
    redirecionar("Senha incorreta.", false);
}

// Sessões
$_SESSION["email"] = $usuario["email"];
$_SESSION["id"] = $usuario["id"];
$_SESSION["tipo"] = $tipo;
$_SESSION["nome"] = $usuario["nome"] ?? $usuario["username"];
if ($tipo === "professor") {
    $_SESSION["turma"] = $usuario["turma"];
}


$_SESSION["mensagem"] = "Login realizado com sucesso!";
$_SESSION["tipoMensagem"] = "sucesso";

switch ($tipo) {
    case 'gestor':
        header("Location: ../dashboard-gestor.php");
        break;
    case 'professor':
        header("Location: ../dashboard-professor.php");
        break;
    case 'responsavel':
        header("Location: ../dashboard-responsavel.php");
        break;
}
exit;
