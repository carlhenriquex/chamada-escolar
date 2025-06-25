<?php
session_start();
include_once("../config/connection.php");

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $senha1 = $_POST["senha1"];
    $senha2 = $_POST["senha2"];

    if ($senha1 !== $senha2) {
        $mensagem = "As senhas não coincidem.";
    } elseif (empty($username) || empty($email) || empty($senha1)) {
        $mensagem = "Preencha todos os campos.";
    } else {
        // Verifica se já existe
        $verifica = $conexao->prepare("SELECT id FROM gestores WHERE email = ?");
        $verifica->bind_param("s", $email);
        $verifica->execute();
        $verifica->store_result();

        if ($verifica->num_rows > 0) {
            $mensagem = "Este e-mail já está cadastrado.";
        } else {
            $senha_hash = password_hash($senha1, PASSWORD_DEFAULT);

            $stmt = $conexao->prepare("INSERT INTO gestores (username, email, senha) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $email, $senha_hash);

            if ($stmt->execute()) {
                $mensagem = "Gestor criado com sucesso!";
            } else {
                $mensagem = "Erro ao cadastrar: " . $stmt->error;
            }
            $stmt->close();
        }

        $verifica->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Gestor</title>
</head>

<body>
    <div class="form-container">
        <h2>Criar Gestor</h2>

        <?php if (!empty($mensagem)) : ?>
            <p class="mensagem"><?= htmlspecialchars($mensagem) ?></p>
        <?php endif; ?>

        <form method="POST">
            <input type="text" name="username" placeholder="Nome de usuário" required>
            <input type="email" name="email" placeholder="E-mail" required>
            <input type="password" name="senha1" placeholder="Senha" required>
            <input type="password" name="senha2" placeholder="Confirmar senha" required>
            <button type="submit">Criar Gestor</button>
        </form>
    </div>
</body>

</html>