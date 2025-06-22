<?php
session_start();

unset($_SESSION["email"]);
unset($_SESSION["nome"]);
unset($_SESSION["id"]);
unset($_SESSION["tipo_acesso"]);
unset($_SESSION["mensagem"]);
header("Location: ../login.php");