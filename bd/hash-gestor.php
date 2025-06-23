execute este arquivo no local host para geral o hash:

<?php
include("../config/connection.php");

$senha = '123';
$hash = password_hash($senha, PASSWORD_DEFAULT);
echo $hash;
?>