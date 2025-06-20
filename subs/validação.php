<?php

session_start();

if (

    isset($_POST['submit']) &&
    (empty($_POST['email']) || empty($_POST['senha']) || empty($_POST['tipo_acesso']))

) {
    $_SESSION["mensagem"] = "Preencha os campos";
    header("Location: ../login.php");
    exit;
}

include("../config/connection.php");

$email = mysqli_real_escape_string($conexao, $_POST['email']);
$password = $_POST['senha'];
$tipo_acesso = $_POST['tipo_acesso'];


switch ($tipo_acesso) {
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
        $_SESSION["mensagem"] = "Selecione um tipo de usuário";
        header("Location: ../login.php");
        break;
        exit;
}

$sql = "SELECT * FROM $table WHERE email = '$email'";

$result = $conexao->query($sql);

if ($result->num_rows == 0) {
    $_SESSION["mensagem"] = "E-mail não encontrado:";
    header("Location: ../login.php");
    exit;
}

$usuario = $result->fetch_assoc();

if ($usuario["senha"] !== $password) {
    $_SESSION["mensagem"] = "Senha incorreta:";
    header("Location: ../login.php");
    exit;
}

$_SESSION["email"] = $email;
$_SESSION["nome"] = $usuario["username"];
$_SESSION["id"] = $usuario["id"];
$_SESSION["tipo_acesso"] = $tipo_acesso;

switch ($tipo_acesso) {
    case 'gestor':
        header("Location: ../dashboard-gestor.html");
        break;

    case 'professor':
        header("Location: ../dashboard-professor.html");
        break;

    case 'responsavel':
        header("Location: ../dashboard-responsavel.html");
        break;
}
exit;