<?php

if (!isset($_SESSION["id"]) || $_SESSION["tipo"] !== $tipoPermitido) {
    $_SESSION["msg"] = "Acesso não autorizado.";
    header("Location: login.php");
    exit;
}
?>