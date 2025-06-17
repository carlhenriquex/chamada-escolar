<?php

    $hostname = "localhost";
    $usuario = "root";
    $password = "";
    $bancodedados = "chamada_escolar";

/*
    $hostname = "192.168.1.72";
    $usuario = "ds2025";
    $password = "123@abcd";
    $bancodedados = "bd_carlosphp_mod2";
*/

    $conexao = new mysqli($hostname, $usuario, $password, $bancodedados);
?>