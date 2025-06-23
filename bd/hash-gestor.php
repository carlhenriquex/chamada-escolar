/* execute este arquivo no local host para geral o hash */
<?php
$senha = '123';
$hash = password_hash($senha, PASSWORD_DEFAULT);
echo $hash;
?>