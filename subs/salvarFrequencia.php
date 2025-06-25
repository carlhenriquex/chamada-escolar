<?php
session_start();
include_once("../config/connection.php");

function redirecionar($mensagem, $sucesso = true, $turma = "") {
    $_SESSION["mensagem"] = $mensagem;
    $hash = "#tela-02";
    $url = "../dashboard-professor.php";
    if (!empty($turma)) {
        $url .= "?turma=" . urlencode($turma);
    }
    header("Location: " . $url . $hash);
    exit;
}

if (!isset($_POST["data"], $_POST["presenca"], $_POST["turma"])) {
    redirecionar("Dados incompletos.", false);
}

$data = $_POST["data"];
$turma = $_POST["turma"];
$presencas = $_POST["presenca"];
$id_professor = $_SESSION["id"];

$erro = false;

foreach ($presencas as $aluno_id => $presente) {
    $status = $presente ? 1 : 0;
    $stmt = $conexao->prepare("INSERT INTO presencas (aluno_id, data, presente, professor_id, turma) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("isiis", $aluno_id, $data, $status, $id_professor, $turma);
    if (!$stmt->execute()) {
        $erro = true;
        break;
    }
}

if ($erro) {
    redirecionar("Erro ao salvar a frequência.", false, $turma);
} else {
    redirecionar("Frequência salva com sucesso!", true, $turma);
}
