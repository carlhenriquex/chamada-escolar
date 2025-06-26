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
    <a href="perfil-responsavel.php" class="perfil-desktop">
      Perfil
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
        <a href="perfil-responsavel.php" class="button-enviar perfil-mobile">
          Perfil
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

            // Mês e ano atuais ou fornecidos via GET
            $mesAtual = isset($_GET["mes"]) ? intval($_GET["mes"]) : date("n"); // 1 a 12
            $anoAtual = isset($_GET["ano"]) ? intval($_GET["ano"]) : date("Y");

            // Normalizar mês/ano para evitar bugs ao voltar de janeiro ou avançar de dezembro
            if ($mesAtual < 1) {
              $mesAtual = 12;
              $anoAtual--;
            } elseif ($mesAtual > 12) {
              $mesAtual = 1;
              $anoAtual++;
            }

            // Cálculo dos limites do mês
            $diasNoMes = cal_days_in_month(CAL_GREGORIAN, $mesAtual, $anoAtual);

            // Buscar presenças
            $sql = "SELECT data_presenca, presente FROM presencas 
          WHERE aluno_id = ? AND MONTH(data_presenca) = ? AND YEAR(data_presenca) = ?
          ORDER BY data_presenca ASC";

            $stmt = $conexao->prepare($sql);
            $stmt->bind_param("iii", $aluno_id, $mesAtual, $anoAtual);
            $stmt->execute();
            $result = $stmt->get_result();

            $presencasPorDia = [];
            while ($linha = $result->fetch_assoc()) {
              $dia = date("j", strtotime($linha["data_presenca"]));
              $presencasPorDia[intval($dia)] = $linha["presente"];
            }

            // Título
            setlocale(LC_TIME, 'pt_BR.utf8');
            $nomeMes = strftime('%B', mktime(0, 0, 0, $mesAtual, 1, $anoAtual));
            echo "<h2>Frequência - " . ucfirst($nomeMes) . " de $anoAtual</h2>";

            // Navegação entre meses
            $mesAnterior = $mesAtual - 1;
            $anoAnterior = $anoAtual;
            if ($mesAnterior < 1) {
              $mesAnterior = 12;
              $anoAnterior--;
            }

            $mesProximo = $mesAtual + 1;
            $anoProximo = $anoAtual;
            if ($mesProximo > 12) {
              $mesProximo = 1;
              $anoProximo++;
            }

            $linkAnterior = "?aluno=$aluno_id&mes=$mesAnterior&ano=$anoAnterior#tela-02";
            $linkProximo = "?aluno=$aluno_id&mes=$mesProximo&ano=$anoProximo#tela-02";


            echo "<div class='navegacao-mes'>";
            echo "<button onclick=\"trocarMes($mesAnterior, $anoAnterior)\" class='botao-mes'>&larr; Mês Anterior</button>";
            echo "<button onclick=\"trocarMes($mesProximo, $anoProximo)\" class='botao-mes'>Próximo Mês &rarr;</button>";
            echo "</div>";

            // Tabela
            echo "<table class='tabela-frequencia-mensal'>";
            echo "<tr><th>Dia</th><th>Status</th></tr>";

            for ($dia = 1; $dia <= $diasNoMes; $dia++) {
              $dataFormatada = str_pad($dia, 2, "0", STR_PAD_LEFT) . "/" . str_pad($mesAtual, 2, "0", STR_PAD_LEFT);
              echo "<tr><td>$dataFormatada</td>";

              if (isset($presencasPorDia[$dia])) {
                $presente = $presencasPorDia[$dia];
                $status = $presente ? "Presente" : "Faltou";
                $classe = $presente ? "status-presente" : "status-faltou";
              } else {
                $status = "–";
                $classe = "status-nao-marcardo";
              }

              echo "<td class='$classe'>$status</td></tr>";
            }

            echo "</table>";
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

          <?php
          if (!isset($_GET["aluno"])) {
            echo "<p>Aluno não selecionado.</p>";
            return;
          }

          $aluno_id = intval($_GET["aluno"]);
          $sql = "SELECT disciplina, unidade, n1, n2, media FROM notas WHERE aluno_id = ? ORDER BY disciplina, unidade";
          $stmt = $conexao->prepare($sql);
          $stmt->bind_param("i", $aluno_id);
          $stmt->execute();
          $result = $stmt->get_result();

          if ($result->num_rows === 0) {
            echo "<p>Nenhuma nota registrada para este aluno.</p>";
            return;
          }

          $notas = [];
          foreach ($result as $row) {
            $notas[$row['unidade']][] = $row;
            $notas['media_final'][$row['disciplina']][] = $row['media'];
          }

          ?>

          <div class="boletim-mobile">
            <div class="botoes-unidades">
              <button onclick="mostrarUnidade(1)">1º Bimestre</button>
              <button onclick="mostrarUnidade(2)">2º Bimestre</button>
              <button onclick="mostrarUnidade(3)">3º Bimestre</button>
              <button onclick="mostrarUnidade(4)">4º Bimestre</button>
              <button onclick="mostrarUnidade('final')">Média Final</button>
            </div>

            <?php for ($u = 1; $u <= 4; $u++): ?>
              <div class="bloco-unidade" id="unidade-<?= $u ?>">
                <h2><?= $u ?>º Bimestre</h2>
                <?php if (!empty($notas[$u])): ?>
                  <?php foreach ($notas[$u] as $nota): ?>
                    <div class="nota-disciplina">
                      <p><strong><?= htmlspecialchars($nota['disciplina']) ?></strong></p>
                      <p><strong>N1:</strong> <?= number_format($nota['n1'], 1, ',', '.') ?></p>
                      <p><strong>N2:</strong> <?= number_format($nota['n2'], 1, ',', '.') ?></p>
                      <p><strong>Média:</strong> <span class="<?= $nota['media'] >= 7 ? 'media-verde' : 'media-vermelha' ?>"><?= number_format($nota['media'], 1, ',', '.') ?></span></p>
                    </div>
                  <?php endforeach; ?>
                <?php else: ?>
                  <p>Sem notas cadastradas neste bimestre.</p>
                <?php endif; ?>
              </div>
            <?php endfor; ?>

            <div class="bloco-unidade" id="unidade-final">
              <h2>Média Final</h2>
              <?php foreach ($notas['media_final'] as $disciplina => $medias):
                $soma = array_sum($medias);
                $mediaFinal = $soma / count($medias);
              ?>
                <div class="nota-disciplina">
                  <p><strong><?= htmlspecialchars($disciplina) ?></strong></p>
                  <p><strong>Média Final:</strong> <span class="<?= $mediaFinal >= 7 ? 'media-verde' : 'media-vermelha' ?>"><?= number_format($mediaFinal, 1, ',', '.') ?></span></p>
                </div>
              <?php endforeach; ?>
            </div>
          </div>


        </div>
      </div>

    </main>
  </div>
</body>
<script src="scripts/dashboard.js"></script>

</html>