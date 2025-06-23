<?php
session_start();

function redirecionar($mensagem)
{
    $_SESSION["msg"] = $mensagem;
    header("Location: ../login.php");
    exit;
}

if (
    isset($_POST['submit']) &&
    (empty($_POST['email']) || empty($_POST['senha'] || empty($_POST['tipo'])))
) {
    redirecionar("Preencha os campos e selecione um usuário");
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
        redirecionar("Selecione um tipo de usuário");
}

$stmt = $conexao->prepare("SELECT * FROM $table WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    redirecionar("E-mail não encontrado.");
}

$usuario = $result->fetch_assoc();

if (!password_verify($password, $usuario["senha"])) {
    redirecionar("Senha incorreta.");
}

// Sessões
$_SESSION["email"] = $email;
$_SESSION["id"] = $usuario["id"];
$_SESSION["tipo"] = $tipo;
$_SESSION["nome"] = $usuario["nome"] ?? $usuario["username"];

// Redirecionamento
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
