<?php
session_start();
include_once("config/connection.php");

$tipoPermitido = 'responsavel';
include("subs/verificaPermissao.php");

$responsavel_id = $_SESSION["id"];

$sql = "SELECT id, nome FROM alunos WHERE responsavel_id = ? AND removido_em IS NULL";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $responsavel_id);
$stmt->execute();
$result = $stmt->get_result();

$alunos = [];
while ($row = $result->fetch_assoc()) {
  $alunos[] = $row;
}


if (!isset($_GET["aluno"]) && count($alunos) > 0) {
  $primeiroId = $alunos[0]["id"];
  header("Location: ?aluno=$primeiroId");
  exit;
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard Responsavel</title>
  <link rel="stylesheet" href="css/main-responsavel.css" />
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
  <header>
    <img src="img/logotexto.png" alt="" />
    <h3>Dashboard Responsável</h3>
    <a
      href="perfil-responsavel.php"
      class="perfil-desktop"
      data-target="tela-04">
      Perfil
      <span class="perfil-icon" aria-hidden="true">
        <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
          <circle cx="10" cy="6.5" r="4" fill="#fff" />
          <path
            d="M3 17c0-2.7614 3.134-5 7-5s7 2.2386 7 5"
            stroke-linecap="round"
            fill="#fff" />
        </svg>
      </span>
    </a>
    <button id="menu-toggle" class="menu-toggle" aria-label="Abrir menu">
      <!-- SVG do menu sanduíche aqui -->
      <svg width="32" height="32" viewBox="0 0 32 32" fill="none">
        <rect y="7" width="32" height="3" rx="1.5" fill="currentColor" />
        <rect y="15" width="32" height="3" rx="1.5" fill="currentColor" />
        <rect y="23" width="32" height="3" rx="1.5" fill="currentColor" />
      </svg>
    </button>
  </header>

  <div class="container">
    <aside class="sidebar" id="sidebar">
      <form method="get" action="">
        <h4 class="sidebar-title">Selecione o Aluno</h4>
        <select class="sidebar-dropdown" name="aluno" id="select-alunos" onchange="this.form.submit()">
          <?php foreach ($alunos as $aluno): ?>
            <option value="<?= $aluno['id'] ?>" <?= ($_GET["aluno"] == $aluno['id']) ? "selected" : "" ?>>
              <?= htmlspecialchars($aluno['nome']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </form>




      <div class="sidebar-buttons">
        <a class="button-enviar" data-target="tela-01">Avisos</a>
        <a class="button-enviar" data-target="tela-02">Frequência</a>
        <a class="button-enviar" data-target="tela-03">Boletim</a>
        <a href="perfil-responsavel.html" class="button-enviar perfil-mobile">
          Perfil
          <span class="perfil-icon" aria-hidden="true">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
              <circle cx="10" cy="6.5" r="4" fill="#fff" />
              <path
                d="M3 17c0-2.7614 3.134-5 7-5s7 2.2386 7 5"
                stroke-linecap="round"
                fill="#fff" />
            </svg>
          </span>
        </a>
        <a class="button-enviar" style="background-color: red;" href="subs/sair.php">Sair</a>
      </div>
    </aside>

    <main>
      <!-- AVISOS -->
      <div class="box-main" id="tela-01">
        <section class="home-section">
          <h1>Avisos da Turma</h1>
          <div class="feed">
            <?php
            include_once("config/connection.php");

            $sql = "SELECT avisos.*, 
              CASE 
                WHEN autor_tipo = 'gestor' THEN gestores.username
                WHEN autor_tipo = 'professor' THEN professores.nome 
              END AS autor_nome
            FROM avisos
            LEFT JOIN gestores ON autor_tipo = 'gestor' AND autor_id = gestores.id
            LEFT JOIN professores ON autor_tipo = 'professor' AND autor_id = professores.id
            ORDER BY data_publicacao DESC
            LIMIT 10";

            $resultado = $conexao->query($sql);

            if ($resultado->num_rows > 0) {
              while ($aviso = $resultado->fetch_assoc()) {
                $id = $aviso['id'];
                echo "<div class='feed-item'>";
                echo "<p><strong>" . htmlspecialchars($aviso['titulo']) . "</strong></p>";
                echo "<div class='repo-card'>" . nl2br(htmlspecialchars($aviso['descricao'])) . "</div>";
                echo "<small>Por " . htmlspecialchars($aviso['autor_nome']) . " em " . date("d/m/Y H:i", strtotime($aviso['data_publicacao'])) . "</small>";

                echo "<form method='post' action='subs/del-edit-aviso.php' style='display:inline;' onsubmit=\"return confirm('Deseja remover este aviso?');\">";
                echo "<input type='hidden' name='delete_id' value='{$id}'>";
                echo "<button type='submit'>Remover</button>";
                echo "</form>";
                echo "</div>";
              }
            } else {
              echo "<p>Nenhum aviso publicado.</p>";
            }
            ?>
          </div>
        </section>
      </div>


      <!-- FREQUÊNCIA -->
      <div class="box-main" id="tela-02">
        <div class="vf-container">
          <?php
          if (!isset($_GET["aluno"])) {
            echo "<p>Aluno não selecionado.</p>";
          } else {
            $aluno_id = intval($_GET["aluno"]);
            $sql = "SELECT data_presenca, presente FROM presencas WHERE aluno_id = ? ORDER BY data_presenca DESC";
            $stmt = $conexao->prepare($sql);

            if (!$stmt) {
              echo "<p>Erro ao preparar a consulta: " . $conexao->error . "</p>";
            } else {
              $stmt->bind_param("i", $aluno_id);
              $stmt->execute();
              $result = $stmt->get_result();

              if ($result->num_rows === 0) {
                echo "<p>Nenhuma frequência registrada para este aluno.</p>";
              } else {
                echo "<table class='tabela-frequencia'>";
                echo "<tr><th>Data</th><th>Status</th></tr>";
                while ($linha = $result->fetch_assoc()) {
                  echo "<tr>";
                  echo "<td>" . date("d/m/Y", strtotime($linha["data_presenca"])) . "</td>";
                  echo "<td>" . ($linha["presente"] ? "Presente" : "Faltou") . "</td>";
                  echo "</tr>";
                }
                echo "</table>";
              }
            }
          }
          ?>
        </div>
      </div>

      <!-- BOLETIM -->
      <div class="box-main" id="tela-03">
        <div class="vn-container">
          <?php
          if (!isset($_GET["aluno"])) {
            echo "<p>Aluno não selecionado.</p>";
          } else {
            $aluno_id = intval($_GET["aluno"]);
            $sql = "SELECT disciplina, unidade, n1, n2, media FROM notas WHERE aluno_id = ? ORDER BY disciplina, unidade";
            $stmt = $conexao->prepare($sql);

            if (!$stmt) {
              echo "<p>Erro ao preparar a consulta: " . $conexao->error . "</p>";
            } else {
              $stmt->bind_param("i", $aluno_id);
              $stmt->execute();
              $result = $stmt->get_result();

              if ($result->num_rows === 0) {
                echo "<p>Nenhuma nota registrada para este aluno.</p>";
              } else {
                $notas = [];

                while ($row = $result->fetch_assoc()) {
                  $disciplina = $row['disciplina'];
                  $unidade = $row['unidade'];
                  $notas[$disciplina][$unidade] = [
                    'n1' => $row['n1'],
                    'n2' => $row['n2'],
                    'media' => $row['media']
                  ];
                }

                echo "<table class='tabela-boletim'>";
                echo "<tr><th rowspan='2'>Disciplina</th>";

                for ($u = 1; $u <= 4; $u++) {
                  echo "<th colspan='3'>{$u}º Bimestre</th>";
                }

                echo "<th rowspan='2'>Média Final</th></tr><tr>";

                for ($u = 1; $u <= 4; $u++) {
                  echo "<th>N1</th><th>N2</th><th>Média</th>";
                }

                echo "</tr>";

                foreach ($notas as $disciplina => $unidades) {
                  echo "<tr>";
                  echo "<td>" . htmlspecialchars($disciplina) . "</td>";

                  $somaMedias = 0;
                  $totalUnidades = 0;

                  for ($u = 1; $u <= 4; $u++) {
                    if (isset($unidades[$u])) {
                      $n1 = number_format($unidades[$u]['n1'], 1, ',', '.');
                      $n2 = number_format($unidades[$u]['n2'], 1, ',', '.');
                      $media = number_format($unidades[$u]['media'], 1, ',', '.');
                      $somaMedias += $unidades[$u]['media'];
                      $totalUnidades++;
                    } else {
                      $n1 = $n2 = $media = "-";
                    }
                    echo "<td>$n1</td><td>$n2</td><td>$media</td>";
                  }

                  $mediaFinal = $totalUnidades > 0 ? $somaMedias / $totalUnidades : 0;
                  $classeMedia = $mediaFinal >= 7 ? 'media-verde' : 'media-vermelha';
                  echo "<td class='{$classeMedia}'>" . number_format($mediaFinal, 1, ',', '.') . "</td>";

                  echo "</tr>";
                }

                echo "</table>";
              }
            }
          }
          ?>
        </div>
      </div>

    </main>
  </div>
</body>
<script src="scripts/dashboard.js"></script>

</html>