<?php
session_start();
include("../config/connection.php");
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


$nome           = $_POST['nome'];
$nascimento     = $_POST['nascimento'];
$rg             = $_POST['rg'];
$cpf            = $_POST['cpf'];
$sexo           = $_POST['sexo'];
$raca           = $_POST['raca'];
$tipo_sanguineo = $_POST['sangue'];
$formacao       = $_POST['formacao'];
$disciplina     = $_POST['disciplina'];
$turma          = $_POST['turma'];
$rua            = $_POST['rua'];
$numero         = $_POST['numero'];
$bairro         = $_POST['bairro'];
$cidade         = $_POST['cidade'];
$complemento    = $_POST['complemento'];
$cep            = $_POST['cep'];
$telefone       = $_POST['telefone'];
$email          = $_POST['email'];
$senha1         = $_POST['senha1'];
$senha2         = $_POST['senha2'];


if ($senha1 !== $senha2) {
    redirecionar("Erro: As senhas não coincidem.", false);
}


$foto = null;
if (isset($_FILES["foto"]) && $_FILES["foto"]["error"] !== UPLOAD_ERR_NO_FILE) {
    $foto = processarUploadImagem($_FILES["foto"], "../uploads/professores/");
    if (!$foto) {
        redirecionar("Erro ao processar a imagem do professor.", false);
    }
}


$verifica = $conexao->prepare("SELECT id FROM professores WHERE email = ?");
$verifica->bind_param("s", $email);
$verifica->execute();
$verifica->store_result();

if ($verifica->num_rows > 0) {
    $verifica->close();
    redirecionar("E-mail já cadastrado no sistema!", false);
}
$verifica->close();


$senha_hash = password_hash($senha1, PASSWORD_DEFAULT);

if ($foto !== null) {
    $stmt = $conexao->prepare("INSERT INTO professores 
    (nome, nascimento, rg, cpf, sexo, raca, tipo_sanguineo, formacao, disciplina, turma,
     rua, numero, bairro, cidade, complemento, cep, telefone, email, senha, foto)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param(
        "ssssssssssssssssssss",
        $nome,
        $nascimento,
        $rg,
        $cpf,
        $sexo,
        $raca,
        $tipo_sanguineo,
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
        $senha_hash,
        $foto
    );
} else {
    $stmt = $conexao->prepare("INSERT INTO professores 
    (nome, nascimento, rg, cpf, sexo, raca, tipo_sanguineo, formacao, disciplina, turma,
     rua, numero, bairro, cidade, complemento, cep, telefone, email, senha)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param(
        "ssssssssssssssssssss",
        $nome,
        $nascimento,
        $rg,
        $cpf,
        $sexo,
        $raca,
        $tipo_sanguineo,
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
        $senha_hash
    );
}

if ($stmt->execute()) {
    redirecionar("Cadastro realizado com sucesso!");
} else {
    redirecionar("Erro ao cadastrar: " . $stmt->error, false);
}

$stmt->close();
$conexao->close();
