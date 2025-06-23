<?php
session_start();
require_once("../config/connection.php");

function redirecionar($msg){

    $_SESSION["mensagem"] = $msg;

    if (isset($_SESSION["tipo"]) && $_SESSION["tipo"] === "professor") {
        header("Location: ../dashboard-professor.php");
    } else {
        header("Location: ../dashboard-gestor.php");
    }
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (empty($_POST["titulo_aviso"]) || empty($_POST["aviso"])) {
        redirecionar("Preencha todos os campos.");
    }

    if (!isset($_SESSION["id"]) || !isset($_SESSION["tipo"])) {
        redirecionar("Erro: usuário não autenticado.");
    }

    $titulo = trim($_POST["titulo_aviso"]);
    $descricao = trim($_POST["aviso"]);

    if (strlen($titulo) > 100) {
        redirecionar("Título muito longo.");
    }

    $autor_id = $_SESSION["id"];
    $autor_tipo = $_SESSION["tipo"];

    $stmt = $conexao->prepare("INSERT INTO avisos (titulo, descricao, autor_id, autor_tipo) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssis", $titulo, $descricao, $autor_id, $autor_tipo);

    if ($stmt->execute()) {
        redirecionar("Aviso publicado com sucesso!");
    } else {
        redirecionar("Erro ao publicar aviso: " . $stmt->error);
    }

    $stmt->close();
    $conexao->close();
}
