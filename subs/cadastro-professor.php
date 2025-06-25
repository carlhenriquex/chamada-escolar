<?php
session_start();
include("../config/connection.php");

function redirecionar($mensagem, $sucesso = true) {
    $_SESSION["mensagem"] = $mensagem;
    $_SESSION["tipoMensagem"] = $sucesso ? "sucesso" : "erro";

    global $stmt, $verifica, $conexao;
    if (isset($stmt)) $stmt->close();
    if (isset($verifica)) $verifica->close();
    if (isset($conexao)) $conexao->close();

    header("Location: ../dashboard-gestor.php#tela-02");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = $_POST['nome'];
    $nascimento = $_POST['nascimento'];
    $rg = $_POST['rg'];
    $cpf = $_POST['cpf'];
    $sexo = $_POST['sexo'];
    $raca = $_POST['raca'];
    $tipo_sanguineo = $_POST['sangue'];
    $formacao = $_POST['formacao'];
    $disciplina = $_POST['disciplina'];
    $turma = $_POST['turma'];
    $rua = $_POST['rua'];
    $numero = $_POST['numero'];
    $bairro = $_POST['bairro'];
    $cidade = $_POST['cidade'];
    $complemento = $_POST['complemento'];
    $cep = $_POST['cep'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];
    $senha = $_POST['senha1'];
    $confirmar_senha = $_POST['senha2'];

    if ($senha !== $confirmar_senha) {
        redirecionar("Erro: As senhas não coincidem.", false);
    }

    $verifica = $conexao->prepare("SELECT id FROM professores WHERE email = ?");
    $verifica->bind_param("s", $email);
    $verifica->execute();
    $verifica->store_result();

    if ($verifica->num_rows > 0) {
        redirecionar("E-mail já cadastrado no sistema!", false);
    }

    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    $stmt = $conexao->prepare("INSERT INTO professores 
        (nome, nascimento, rg, cpf, sexo, raca, tipo_sanguineo, formacao, disciplina, turma, rua, numero, bairro, cidade, complemento, cep, telefone, email, senha)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param(
        "sssssssssssssssssss",
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

    if ($stmt->execute()) {
        redirecionar("Cadastro realizado com sucesso!");
    } else {
        redirecionar("Erro ao cadastrar: " . $stmt->error, false);
    }

    $stmt->close();
    $verifica->close();
    $conexao->close();
}
