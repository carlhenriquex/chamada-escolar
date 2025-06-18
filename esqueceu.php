<?php

session_start();

include_once("config/connection.php");

if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($conexao, $_POST['email']);
    $senha1 = $_POST['senha1'];
    $senha2 = $_POST['senha2'];

    if ($senha1 !== $senha2) {
        $_SESSION['mensagem'] = "As senhas não coincidem!";
        header("Location: esqueceu.php");
        exit();
    }

    $tabelas = ['gestores', 'professores', 'responsaveis'];
    $tabela_encontrada = null;

    foreach ($tabelas as $tabela) {
        $query = mysqli_query($conexao, "SELECT * FROM $tabela WHERE email = '$email'");
        if (mysqli_num_rows($query) > 0) {
            $tabela_encontrada = $tabela;
            break;
        }
    }

    if (!$tabela_encontrada) {
        $_SESSION['mensagem'] = "E-mail não encontrado em nenhum perfil.";
        header("Location: esqueceu.php");
        exit();
    }

    $update = mysqli_query($conexao, "UPDATE $tabela_encontrada SET senha = '$senha1' WHERE email = '$email'");

    if ($update) {
        $_SESSION['mensagem'] = "Senha atualizada com sucesso!";
    } else {
        $_SESSION['mensagem'] = "Erro ao atualizar a senha.";
    }

    header("Location: esqueceu.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir senha</title>
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

</head>

<body>
    <a href="index.html" class="btn-voltar">
        <i class="fas fa-arrow-left"></i>
    </a>

    <div class="container-esquerdo">
        <img class="banner-img" src="img/banner-login.jpg" alt="Foto de banner da tela de login">
    </div>

    <div class="container-direito">

        <form action="esqueceu.php" method="post">

            <?php
            if (isset($_SESSION["mensagem"])) {
                echo "<p class='mensagem'>" . $_SESSION["mensagem"] . "</p>";
                unset($_SESSION["mensagem"]);
            }
            ?>
            <img src="img/rodape_logo.png" class="logo-img" alt="Logo Chamada Escolar">

            <div class="input-group">
                <img src="img/e-mail.png" alt="Ícone E-mail">
                <input type="email" name="email" placeholder="Digite o seu E-mail" required>
            </div>

            <div class="input-group">
                <img src="img/senha.png" alt="Ícone Senha">
                <input type="password" name="senha1" placeholder="Digite sua nova senha" required>
            </div>

            <div class="input-group">
                <img src="img/senha.png" alt="Ícone Senha">
                <input type="password" name="senha2" placeholder="Confirme sua nova senha" required>
            </div>

            <button type="submit" name="submit" class="login-button">Salvar</button>

        </form>
    </div>
</body>

</html>