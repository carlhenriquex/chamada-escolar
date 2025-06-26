<?php
session_start();
include_once("../config/connection.php");
include_once("../functions/functionImage.php");

function redirecionar($mensagem, $sucesso = true, $stmt = null, $verifica = null, $conexao = null)
{
    $_SESSION["mensagem"] = $mensagem;
    $_SESSION["tipoMensagem"] = $sucesso ? "sucesso" : "erro";

    if ($stmt instanceof mysqli_stmt) {
        @$stmt->close();
    }
    if ($verifica instanceof mysqli_stmt) {
        @$verifica->close();
    }
    if ($conexao instanceof mysqli) {
        @$conexao->close();
    }

    header("Location: ../dashboard-gestor.php#tela-01");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome         = $_POST['nome'];
    $nascimento   = $_POST['nascimento'];
    $rg           = $_POST['rg'];
    $cpf          = $_POST['cpf'];
    $sexo         = $_POST['sexo'];
    $raca         = $_POST['raca'];
    $sangue       = $_POST['sangue'];
    $nacionalidade = $_POST['nacionalidade'];
    $naturalidade = $_POST['naturalidade'];
    $turma        = $_POST['turma'];
    $deficiencia  = !empty($_POST['deficiencia']) ? $_POST['deficiencia'] : NULL;

    $foto = null;
    if (isset($_FILES["foto"]) && $_FILES["foto"]["error"] !== UPLOAD_ERR_NO_FILE) {
        $foto = processarUploadImagem("foto", "../uploads/alunos/");
        if (!$foto) {
            redirecionar("Erro ao processar a imagem do aluno.", false);
        }
    }

    $emailResponsavelExistente = trim($_POST['responsavel']);

    $stmt = null;
    $verifica = null;

    if (!empty($emailResponsavelExistente)) {
        $verifica = $conexao->prepare("SELECT id FROM responsaveis WHERE email = ?");
        $verifica->bind_param("s", $emailResponsavelExistente);
        $verifica->execute();
        $verifica->bind_result($responsavel_id);

        if ($verifica->fetch()) {
            $verifica->close();
        } else {
            redirecionar("Responsável com e-mail informado não encontrado.", false, null, $verifica, $conexao);
        }
    } else {
        $nome_resp   = $_POST['nome_responsavel'];
        $rg_resp     = $_POST['rg_responsavel'];
        $cpf_resp    = $_POST['cpf_responsavel'];
        $parentesco  = $_POST['parentesto'];
        $rua         = $_POST['rua'];
        $numero      = $_POST['numero'];
        $bairro      = $_POST['bairro'];
        $cidade      = $_POST['cidade'];
        $complemento = $_POST['complemento'];
        $cep         = $_POST['cep'];
        $telefone    = $_POST['telefone'];
        $email_resp  = $_POST['email'];
        $senha1      = $_POST['senha1'];
        $senha2      = $_POST['senha2'];

        if ($senha1 !== $senha2) {
            redirecionar("As senhas do responsável não coincidem.", false);
        }

        $verifica = $conexao->prepare("SELECT id FROM responsaveis WHERE email = ?");
        $verifica->bind_param("s", $email_resp);
        $verifica->execute();
        $verifica->store_result();

        if ($verifica->num_rows > 0) {
            redirecionar("Erro: E-mail do responsável já cadastrado.", false, null, $verifica, $conexao);
        }

        $verifica->close();

        $senha_hash = password_hash($senha1, PASSWORD_DEFAULT);

        $stmt = $conexao->prepare("INSERT INTO responsaveis 
            (nome, rg, cpf, parentesco, rua, numero, bairro, cidade, complemento, cep, telefone, email, senha) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->bind_param("sssssssssssss", $nome_resp, $rg_resp, $cpf_resp, $parentesco, $rua, $numero, $bairro, $cidade, $complemento, $cep, $telefone, $email_resp, $senha_hash);

        if ($stmt->execute()) {
            $responsavel_id = $stmt->insert_id;
            $stmt->close();
        } else {
            redirecionar("Erro ao cadastrar responsável: " . $stmt->error, false, $stmt, null, $conexao);
        }
    }

    if ($foto !== null) {
        $stmt = $conexao->prepare("INSERT INTO alunos 
        (nome, nascimento, rg, cpf, sexo, raca, tipo_sanguineo, nacionalidade, naturalidade, turma, deficiencia, responsavel_id, foto) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->bind_param("sssssssssssis", $nome, $nascimento, $rg, $cpf, $sexo, $raca, $sangue, $nacionalidade, $naturalidade, $turma, $deficiencia, $responsavel_id, $foto);
    } else {
        $stmt = $conexao->prepare("INSERT INTO alunos 
        (nome, nascimento, rg, cpf, sexo, raca, tipo_sanguineo, nacionalidade, naturalidade, turma, deficiencia, responsavel_id) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->bind_param("sssssssssssi", $nome, $nascimento, $rg, $cpf, $sexo, $raca, $sangue, $nacionalidade, $naturalidade, $turma, $deficiencia, $responsavel_id);
    }

    if ($stmt->execute()) {
        redirecionar("Cadastro do aluno realizado com sucesso!", true, $stmt, null, $conexao);
    } else {
        redirecionar("Erro ao cadastrar aluno: " . $stmt->error, false, $stmt, null, $conexao);
    }
}
