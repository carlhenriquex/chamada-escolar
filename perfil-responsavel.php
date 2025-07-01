<?php
session_start();
include_once("config/connection.php");

if (!isset($_SESSION["id"]) || $_SESSION["tipo"] !== "responsavel") {
  header("Location: login.php");
  exit;
}

$responsavel_id = $_SESSION["id"];

// Função fallback
function safe($valor)
{
  return !empty($valor) ? htmlspecialchars($valor) : "N/A";
}

// Dados do responsável
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

$fotoPerfil = (!empty($foto) && file_exists("uploads/responsaveis/" . $foto))
  ? "uploads/responsaveis/$foto"
  : "img/user-default.png";
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

  <?php
  if (isset($_SESSION["mensagem"])) {
    $tipo = $_SESSION["tipoMensagem"] ?? "sucesso";
    echo "<div class='mensagem {$tipo}'>";
    echo "<p class='mensagemText'>" . $_SESSION["mensagem"] . "</p>";
    unset($_SESSION["mensagem"]);
    echo "</div>";
  }
  ?>

  <a href="dashboard-responsavel.php" class="btn-voltar">
    <i class="bi bi-arrow-left"></i>
  </a>
  <div class="container">
    <h1>Perfil do Responsável</h1>

    <div class="perfil-section">

      <!-- FOTO DE PERFIL -->
      <div class="perfil-foto">
        <img src="<?= safe($fotoPerfil) ?>" alt="Foto de Perfil" class="foto-perfil" />
        <form action="subs/atualizarFotoResponsavel.php" method="post" enctype="multipart/form-data">
          <label for="nova-foto">Alterar foto:</label>
          <input type="file" name="nova_foto" id="nova-foto" accept="image/*" required />
          <button type="submit">Salvar nova foto</button>
        </form>
      </div>

      <!-- DADOS -->
      <div class="perfil-dados">
        <p><strong>Nome:</strong> <?= safe($nome) ?></p>
        <p><strong>Telefone:</strong> <?= safe($telefone) ?></p>
        <p><strong>Email:</strong> <?= safe($email) ?></p>
        <p><strong>Endereço:</strong> <?= safe("$rua, Nº $numero - $bairro, $cidade") ?></p>
      </div>


    </div>
    <!-- FILHOS -->
    <div class="perfil-filhos">
      <h2>Filhos Cadastrados</h2>
      <?php if (count($filhos) > 0): ?>
        <ul>
          <?php foreach ($filhos as $filho): ?>
            <li>
              <?= safe($filho["nome"]) ?> —
              Turma: <?= safe($filho["turma"]) ?> —
              Parentesco: <?= safe($parentesco) ?> —
              Nascimento: <?= safe(date("d/m/Y", strtotime($filho["nascimento"]))) ?> —
              Tipo sanguíneo: <?= safe($filho["tipo_sanguineo"]) ?> —
              Deficiência: <?= safe($filho["deficiencia"]) ?>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php else: ?>
        <p>Nenhum filho(a) cadastrado.</p>
      <?php endif; ?>
    </div>
  </div>
</body>

</html>