<?php
session_start();
include_once("../config/connection.php");

function redirecionar($mensagem, $sucesso = true, $turma = "") {
    $_SESSION["mensagem"] = $mensagem;
    $_SESSION["tipoMensagem"] = $sucesso ? "sucesso" : "erro";

    $hash = "#tela-02";
    $url = "../dashboard-professor.php";
    if (!empty($turma)) {
        $url .= "?turma=" . urlencode($turma);
    }
    header("Location: " . $url . $hash);
    exit;
}

// Verificação inicial
if (!isset($_POST["data_presenca"], $_POST["turma"])) {
    redirecionar("Dados incompletos.", false);
}

$data = $_POST["data_presenca"];
$turma = trim($_POST["turma"]);
$presencasMarcadas = $_POST["presenca"] ?? [];

$id_professor = $_SESSION["id"] ?? null;
if (!$id_professor) {
    redirecionar("Sessão inválida. Faça login novamente.", false);
}

// Buscar disciplina do professor
$stmtProf = $conexao->prepare("SELECT disciplina FROM professores WHERE id = ?");
$stmtProf->bind_param("i", $id_professor);
$stmtProf->execute();
$resProf = $stmtProf->get_result();

if ($resProf->num_rows === 0) {
    redirecionar("Disciplina não encontrada para este professor.", false, $turma);
}
$disciplina = $resProf->fetch_assoc()["disciplina"];
$stmtProf->close();

// Buscar todos os alunos da turma
$stmtAlunos = $conexao->prepare("SELECT id FROM alunos WHERE turma = ? AND removido_em IS NULL");
$stmtAlunos->bind_param("s", $turma);
$stmtAlunos->execute();
$resultado = $stmtAlunos->get_result();

// Query de inserção/atualização
$sql = "INSERT INTO presencas (aluno_id, data_presenca, presente, turma, disciplina)
        VALUES (?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE presente = VALUES(presente), data_registro = CURRENT_TIMESTAMP";
$stmtInsert = $conexao->prepare($sql);

$erro = false;
while ($aluno = $resultado->fetch_assoc()) {
    $aluno_id = $aluno["id"];
    $presente = isset($presencasMarcadas[$aluno_id]) ? 1 : 0;

    $stmtInsert->bind_param("isiss", $aluno_id, $data, $presente, $turma, $disciplina);
    if (!$stmtInsert->execute()) {
        $erro = true;
        break;
    }
}

$stmtAlunos->close();
$stmtInsert->close();

if ($erro) {
    redirecionar("Erro ao salvar a frequência.", false, $turma);
} else {
    redirecionar("Frequência salva com sucesso!", true, $turma);
}
