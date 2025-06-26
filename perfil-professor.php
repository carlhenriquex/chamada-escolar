<?php
session_start();
include_once("config/connection.php");

$professor_id = $_SESSION["id"] ?? null;

if (!$professor_id) {
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

// Dados tratados com fallback para "N/A"
function safe($value) {
  return !empty($value) ? htmlspecialchars($value) : "N/A";
}

$fotoPath = file_exists("uploads/professores/{$prof['id']}.jpg")
  ? "uploads/professores/{$prof['id']}.jpg"
  : "img/perfil-generico.png";
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Perfil do Professor</title>
  <link rel="stylesheet" href="css/perfil.css" />
</head>
<body>
  <div class="container">
    <h1>Perfil do Professor</h1>

    <div class="perfil-foto">
      <img src="<?= $fotoPath ?>" alt="Foto de perfil do professor">
      <form action="subs/atualizar-foto-professor.php" method="post" enctype="multipart/form-data">
        <input type="file" name="nova_foto" accept="image/*">
        <button type="submit">Atualizar Foto</button>
      </form>
    </div>

    <div class="perfil-info">
      <p><strong>Nome:</strong> <?= safe($prof["nome"]) ?></p>
      <p><strong>Nascimento:</strong> <?= safe(date("d/m/Y", strtotime($prof["nascimento"]))) ?></p>
      <p><strong>RG:</strong> <?= safe($prof["rg"]) ?></p>
      <p><strong>CPF:</strong> <?= safe($prof["cpf"]) ?></p>
      <p><strong>Sexo:</strong> <?= safe($prof["sexo"]) ?></p>
      <p><strong>Cor/Raça:</strong> <?= safe($prof["raca"]) ?></p>
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
