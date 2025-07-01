<?php
session_start();
include_once("config/connection.php");

function redirecionar($mensagem, $sucesso = true) {
  $_SESSION["mensagem"] = $mensagem;
  $_SESSION["tipoMensagem"] = $sucesso ? "sucesso" : "erro";
  header("Location: cadastro-escola.php");
  exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

  $nome = $_POST["nome_contato"] ?? '';
  $email = $_POST["email"] ?? '';
  $telefone = $_POST["telefone"] ?? '';
  $nome_escola = $_POST["nome_escola"] ?? '';
  $setor = $_POST["setor_educacional"] ?? '';
  $relacao = $_POST["relacao_escola"] ?? '';
  $porte = $_POST["porte_alunos"] ?? '';
  $senha = $_POST["senha"] ?? '';
  $confirmar = $_POST["confirmar_senha"] ?? '';
  $aceitou = isset($_POST["aceitar_termos"]);

  // Validação básica
  if (!$aceitou) {
    redirecionar("Você precisa aceitar os termos e a política de privacidade.", false);
  }

  if ($senha !== $confirmar) {
    redirecionar("As senhas não coincidem.", false);
  }

  if (empty($nome) || empty($email) || empty($nome_escola) || empty($setor) || empty($relacao) || empty($porte)) {
    redirecionar("Preencha todos os campos obrigatórios.", false);
  }

  $sqlVerifica = "SELECT id FROM escolas WHERE email = ?";
  $stmtVerifica = $conexao->prepare($sqlVerifica);
  $stmtVerifica->bind_param("s", $email);
  $stmtVerifica->execute();
  $stmtVerifica->store_result();

  if ($stmtVerifica->num_rows > 0) {
    redirecionar("Este e-mail já está cadastrado.", false);
  }

  // Hash da senha
  $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

  $sql = "INSERT INTO escolas (nome_contato, email, telefone, nome_escola, setor_educacional, relacao_escola, porte_alunos, senha)
          VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

  $stmt = $conexao->prepare($sql);
  $stmt->bind_param("ssssssss", $nome, $email, $telefone, $nome_escola, $setor, $relacao, $porte, $senhaHash);

  if ($stmt->execute()) {
    redirecionar("Cadastro realizado com sucesso!");
  } else {
    redirecionar("Erro ao cadastrar: " . $stmt->error, false);
  }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>cadastro</title>
    <link rel="stylesheet" href="css/cadastro-escola.css">
    <!-- Bootstrap 4 -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Font Awesome (ícones) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

</head>

<body>

    <?php
    if (isset($_SESSION["mensagem"])) {
        $tipo = $_SESSION["tipoMensagem"] ?? "sucesso";
        echo "<div class='mensagem {$tipo}'>";
        echo "<p class='mensagemText'>" . $_SESSION["mensagem"] . "</p>";
        unset($_SESSION["mensagem"]);
        echo "</div>";
    }
    ?>
    <!-- Ícone Voltar -->
    <a href="index.php" class="btn-voltar">
        <i class="fas fa-arrow-left"></i>
    </a>

    <div class="container-esquerdo">
        <img class="banner-img" src="img/banner-cadastro.webp" alt="Foto de banner da tela de login">
    </div>

    <!-- <main class="main-content"> -->
    <form class="form-escola" method="POST" action="cadastro-escola.php">

        <div class="row">
            <img src="img/rodape_logo.png" class="logo-img" alt="Logo Chamada Escolar">
        </div>

        <div class="row">
            <div class="input-group">
                <img src="img/usuario.png" alt="Ícone Usuário">
                <input type="text" name="nome_contato" placeholder="Nome" required>
            </div>
        </div>

        <div class="row">
            <div class="input-group">
                <img src="img/e-mail.png" alt="Ícone email">
                <input type="email" name="email" placeholder="E-mail" required>
            </div>

            <div class="input-group">
                <img src="img/telefone.png" alt="Ícone Telefone">
                <input type="tel" name="telefone" placeholder="Telefone">
            </div>
        </div>

        <div class="row">
            <div class="input-group">
                <input type="text" name="nome_escola" placeholder="Nome da Escola" required>
            </div>
            <div class="input-group">
                <select id="setor" name="setor_educacional" required>
                    <option value="" disabled selected>Setor Educacional</option>
                    <option value="infantil">Educação Infantil</option>
                    <option value="fundamental1">Ensino Fundamental – Iniciais</option>
                    <option value="fundamental2">Ensino Fundamental – Finais</option>
                    <option value="medio">Ensino Médio</option>
                </select>
            </div>
        </div>

        <div class="row">
            <div class="input-group">
                <select id="relacao" name="relacao_escola" required>
                    <option value="" disabled selected>Relação com a escola</option>
                    <option value="diretor">Diretor(a)</option>
                    <option value="coordenador">Coordenador(a)</option>
                    <option value="professor">Professor(a)</option>
                    <option value="outro">Outro</option>
                </select>
            </div>

            <div class="input-group">
                <select id="porte" name="porte_alunos" required>
                    <option value="" disabled selected>Porte de alunos</option>
                    <option value="pequeno">Até 100 alunos</option>
                    <option value="medio">101 a 500 alunos</option>
                    <option value="grande">Mais de 500 alunos</option>
                </select>
            </div>
        </div>

        <div class="row">
            <div class="input-group">
                <img src="img/senha.png" alt="Ícone Senha">
                <input type="password" name="senha" placeholder="Senha" required>
            </div>

            <div class="input-group">
                <img src="img/senha.png" alt="Ícone Confirmar Senha">
                <input type="password" name="confirmar_senha" placeholder="Repetir senha" required>
            </div>
        </div>

        <div class="row">
            <input type="checkbox" id="aceitar_termos" name="aceitar_termos" required>
            <label for="aceitar_termos">Li e concordo com <a href="#">Termos e Privacidade</a></label>
        </div>

        <div class="row">
            <button class="button-criar-conta" type="submit">Criar Conta</button>
        </div>
    </form>

</body>

</html>