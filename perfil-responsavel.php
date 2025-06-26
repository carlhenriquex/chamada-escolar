<?php
session_start();
include_once("config/connection.php");

if (!isset($_SESSION["id"]) || $_SESSION["tipo"] !== "responsavel") {
  header("Location: login.php");
  exit;
}

$responsavel_id = $_SESSION["id"];


$sql = "SELECT nome, foto, telefone, email, rua, bairro, cidade, numero, parentesco FROM responsaveis WHERE id = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $responsavel_id);
$stmt->execute();
$stmt->bind_result($nome, $foto, $telefone, $email, $rua, $bairro, $cidade, $numero, $parentesco);
$stmt->fetch();
$stmt->close();

// Buscar filhos
$sqlFilhos = "SELECT nome, turma, nascimento, tipo_sanguineo, deficiencia FROM alunos WHERE responsavel_id = ? AND removido_em IS NULL";
$stmt = $conexao->prepare($sqlFilhos);
$stmt->bind_param("i", $responsavel_id);
$stmt->execute();
$resultFilhos = $stmt->get_result();
$filhos = [];
while ($row = $resultFilhos->fetch_assoc()) {
  $filhos[] = $row;
}
$stmt->close();

$fotoPerfil = (!empty($foto) && file_exists("uploads/responsaveis/" . $foto)) ? "uploads/responsaveis/$foto" : "img/user-default.png";
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Perfil do Responsável</title>
  <link rel="stylesheet" href="css/perfil.css" />
</head>

<body>

  <div class="container">
    <h1>Perfil do Responsável</h1>

    <div class="perfil-section">

      <!-- FOTO DE PERFIL -->
      <div class="perfil-foto">
        <img src="<?= htmlspecialchars($fotoPerfil) ?>" alt="Foto de Perfil" class="foto-perfil" />
        <form action="subs/atualizar-foto-responsavel.php" method="post" enctype="multipart/form-data">
          <label for="nova-foto">Alterar foto:</label>
          <input type="file" name="nova_foto" id="nova-foto" accept="image/*" required />
          <button type="submit">Salvar nova foto</button>
        </form>
      </div>

      <!-- DADOS -->
      <div class="perfil-dados">
        <p><strong>Nome:</strong> <?= htmlspecialchars($nome) ?></p>
        <p><strong>Telefone:</strong> <?= htmlspecialchars($telefone) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($email) ?></p>
        <p><strong>Endereço:</strong> <?= htmlspecialchars("$rua, Nº $numero - $bairro, $cidade") ?></p>
      </div>

      <!-- FILHOS -->
      <div class="perfil-filhos">
        <h2>Filhos Cadastrados</h2>
        <?php if (count($filhos) > 0): ?>
          <ul>
            <?php foreach ($filhos as $filho): ?>
              <li>
                <?= htmlspecialchars($filho["nome"]) ?> -
                Turma: <?= htmlspecialchars($filho["turma"]) ?> -
                Parentesco: <?= htmlspecialchars($parentesco) ?> -
                Turma: <?= htmlspecialchars($filho["nascimento"]) ?> -
                Tipo sanguíneo: <?= htmlspecialchars($filho["tipo_sanguineo"]) ?> -
                Deficiencia: <?= htmlspecialchars($filho["deficiencia"]) ?>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php else: ?>
          <p>Nenhum filho(a) cadastrado.</p>
        <?php endif; ?>
      </div>

    </div>
  </div>

</body>

</html>