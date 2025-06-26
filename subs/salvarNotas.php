<?php
session_start();
include_once("../config/connection.php");

$tipoPermitido = 'professor';
include_once("verificaPermissao.php");

function redirecionar($mensagem, $turma, $sucesso = true) {
    $_SESSION["mensagem"] = $mensagem;
    $_SESSION["tipoMensagem"] = $sucesso ? "sucesso" : "erro";
    header("Location: ../dashboard-professor.php?turma=" . urlencode($turma) . "#tela-03");
    exit;
}

$turma = $_POST["turma"] ?? '';
$turma = trim($turma);

if ($turma === '') {
    redirecionar("Turma não especificada.", $turma, false);
}

if (!isset($_POST["notas"])) {
    redirecionar("Nenhuma nota foi enviada.", $turma, false);
}

$professorId = $_SESSION["id"] ?? null;

if (!$professorId) {
    redirecionar("Sessão inválida. Faça login novamente.", $turma, false);
}

$stmtDisc = $conexao->prepare("SELECT disciplina FROM professores WHERE id = ?");
$stmtDisc->bind_param("i", $professorId);
$stmtDisc->execute();
$result = $stmtDisc->get_result();
$row = $result->fetch_assoc();
$stmtDisc->close();

if (!$row || empty($row["disciplina"])) {
    redirecionar("Erro ao obter a disciplina do professor.", $turma, false);
}

$disciplina = $row["disciplina"];
$notasRecebidas = $_POST["notas"];

$sql = "INSERT INTO notas (aluno_id, disciplina, unidade, n1, n2)
        VALUES (?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE
            n1 = IF(VALUES(n1) IS NULL, n1, VALUES(n1)),
            n2 = IF(VALUES(n2) IS NULL, n2, VALUES(n2))";

$stmt = $conexao->prepare($sql);

$notasSalvas = false;

foreach ($notasRecebidas as $unidade => $alunos) {
    foreach ($alunos as $alunoId => $notas) {
        $n1Preenchido = isset($notas["n1"]) && $notas["n1"] !== '';
        $n2Preenchido = isset($notas["n2"]) && $notas["n2"] !== '';

        if (!$n1Preenchido && !$n2Preenchido) {
            continue;
        }

        $n1 = $n1Preenchido ? floatval($notas["n1"]) : null;
        $n2 = $n2Preenchido ? floatval($notas["n2"]) : null;

        $stmt->bind_param("isidd", $alunoId, $disciplina, $unidade, $n1, $n2);
        if ($stmt->execute()) {
            $notasSalvas = true;
        }
    }
}

$stmt->close();

if ($notasSalvas) {
    redirecionar("Notas salvas com sucesso.", $turma, true);
} else {
    redirecionar("Nenhuma nota foi salva. Preencha pelo menos um campo.", $turma, false);
}
