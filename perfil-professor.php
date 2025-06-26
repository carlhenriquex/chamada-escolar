<?php
session_start();
include_once("config/connection.php");

$professor_id = $_SESSION["id"] ?? null;

if (!$professor_id || $_SESSION["tipo"] !== "professor") {
  echo "Professor não autenticado.";
  exit;
}

$sql = "SELECT * FROM professores WHERE id = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $professor_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
  echo "Professor não encontrado.";
  exit;
}

$prof = $result->fetch_assoc();

// Função para exibir valor ou "N/A"
function safe($value)
{
  return !empty($value) ? htmlspecialchars($value) : "N/A";
}

// Caminho da foto de perfil
$fotoNome = $prof['foto'] ?? '';
$fotoPath = file_exists("uploads/professores/{$fotoNome}") && !empty($fotoNome)
  ? "uploads/professores/{$fotoNome}"
  : "img/perfilGenerico.png";
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <title>Perfil do Professor</title>
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

  <a href="dashboard-professor.php" class="btn-voltar">
    <i class="bi bi-arrow-left"></i>
  </a>

  <div class="container">
    <h1>Perfil do Professor</h1>

    <div class="perfil-foto">
      <img src="<?= $fotoPath ?>" alt="Foto de perfil do professor" class="foto-perfil">
      <form action="subs/atualizarFotoProfessor.php" method="post" enctype="multipart/form-data">
        <label for="nova-foto">Alterar foto:</label>
        <input type="file" name="nova_foto" id="nova-foto" accept="image/*" required>
        <button type="submit">Salvar nova foto</button>
      </form>
    </div>

    <div class="perfil-info">
      <p><strong>Nome:</strong> <?= safe($prof["nome"]) ?></p>
      <p><strong>Nascimento:</strong> <?= safe(date("d/m/Y", strtotime($prof["nascimento"]))) ?></p>
      <p><strong>Tipo Sanguíneo:</strong> <?= safe($prof["tipo_sanguineo"]) ?></p>
      <p><strong>Formação:</strong> <?= safe($prof["formacao"]) ?></p>
      <p><strong>Disciplina:</strong> <?= safe($prof["disciplina"]) ?></p>

      <h3>Endereço</h3>
      <p><strong>Rua:</strong> <?= safe($prof["rua"]) ?></p>
      <p><strong>Número:</strong> <?= safe($prof["numero"]) ?></p>
      <p><strong>Bairro:</strong> <?= safe($prof["bairro"]) ?></p>
      <p><strong>Cidade:</strong> <?= safe($prof["cidade"]) ?></p>
      <p><strong>Complemento:</strong> <?= safe($prof["complemento"]) ?></p>
      <p><strong>CEP:</strong> <?= safe($prof["cep"]) ?></p>

      <h3>Contato</h3>
      <p><strong>Telefone:</strong> <?= safe($prof["telefone"]) ?></p>
      <p><strong>Email:</strong> <?= safe($prof["email"]) ?></p>
    </div>
  </div>
</body>

</html>