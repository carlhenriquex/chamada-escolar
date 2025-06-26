<?php
session_start();
$tipoPermitido = 'professor';
$turma = $_GET["turma"] ?? "6º Ano";

include_once("config/connection.php");
include("subs/verificaPermissao.php");

$sql = "SELECT aluno_id, unidade, n1, n2, media FROM notas 
WHERE aluno_id IN (SELECT id FROM alunos WHERE turma = ? AND removido_em IS NULL)";

$stmt = $conexao->prepare($sql);
$stmt->bind_param("s", $turma);
$stmt->execute();
$resultado = $stmt->get_result();

$notas = [];

while ($row = $resultado->fetch_assoc()) {
  $alunoId = $row['aluno_id'];
  $unidade = $row['unidade'];

  $notas[$unidade][$alunoId] = [
    'n1' => $row['n1'],
    'n2' => $row['n2'],
    'media' => $row['media'],
  ];
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard Professor</title>
  <link rel="stylesheet" href="css/main-professor.css" />
</head>

<body>

  <?php
    if (isset($_SESSION["mensagem"])) {
      $tipo = $_SESSION["tipoMensagem"] ?? "sucesso";
      echo"<div class='mensagem {$tipo}'>";
      echo "<p class='mensagemText'>" . $_SESSION["mensagem"] . "</p>";
      unset($_SESSION["mensagem"]);
      echo"</div>";
    }
    ?>

  <header>
    <img src="img/logotexto.png" alt="" />
    <h3>Dashboard Professor</h3>
    <a
      href="perfil-professor.php"
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
        <h4 class="sidebar-title">Selecione a Turma</h4>
        <select class="sidebar-dropdown" name="turma" id="select-alunos" onchange="this.form.submit()">
          <option value="6º Ano" <?= (isset($_GET["turma"]) && $_GET["turma"] === "6º Ano") ? "selected" : "" ?>>6º Ano</option>
          <option value="7º Ano" <?= (isset($_GET["turma"]) && $_GET["turma"] === "7º Ano") ? "selected" : "" ?>>7º Ano</option>
          <option value="8º Ano" <?= (isset($_GET["turma"]) && $_GET["turma"] === "8º Ano") ? "selected" : "" ?>>8º Ano</option>
          <option value="9º Ano" <?= (isset($_GET["turma"]) && $_GET["turma"] === "9º Ano") ? "selected" : "" ?>>9º Ano</option>
        </select>
      </form>

      <div class="sidebar-buttons">
        <a class="button-enviar" data-target="tela-01">Avisos</a>
        <a class="button-enviar" data-target="tela-02">Frequência</a>
        <a class="button-enviar" data-target="tela-03">Boletim</a>
        <a href="perfil-professor.html" class="button-enviar perfil-mobile">
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
      <div class="box-main" id="tela-01">
        <form action="subs/addaviso.php" method="post" class="main-header">
          <div class="main-header-row">
            <h4>Adicione um novo aviso:</h4>
            <button type="submit" class="button-enviar-laranja">Enviar</button>
          </div>
          <input type="text" placeholder="Título" name="titulo_aviso" class="title-avisos" required />
          <textarea placeholder="Descrição" rows="2" name="aviso" class="desc-avisos" required></textarea>
        </form>

        <section class="home-section">
          <h1>Avisos da Turma</h1>
          <div class="feed">
            <?php

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

      <div class="box-main" id="tela-02">
        <!-- html-lancar-frequencia -->

        <div class="lf-container">
          <h1 class="lf-title">Lançar Frequência</h1>

          <form method="post" action="subs/salvarFrequencia.php">

            <input type="hidden" name="turma" value="<?= htmlspecialchars($turma) ?>">
            <h3 class="lf-subtitle">Selecione a data</h3>
            <input type="date" class="lf-input-date" name="data_presenca" required />

            <table class="lf-tabela">
              <thead>
                <tr class="lf-header-tabela">
                  <th>Aluno</th>
                  <th>Status presença</th>
                </tr>
              </thead>
              <tbody>
              <tbody>
                <?php

                $turma = $_GET["turma"] ?? "6º Ano";

                $stmt = $conexao->prepare("SELECT id, nome FROM alunos WHERE turma = ? AND removido_em IS NULL ORDER BY nome");
                $stmt->bind_param("s", $turma);
                $stmt->execute();
                $alunos = $stmt->get_result();

                if ($alunos && $alunos->num_rows > 0): ?>
                  <?php while ($aluno = $alunos->fetch_assoc()) { ?>
                    <tr>
                      <td><?= htmlspecialchars($aluno["nome"]) ?></td>
                      <td>
                        <input type="checkbox" class="lf-checkbox" name="presenca[<?= $aluno["id"] ?>]" checked>
                      </td>
                    </tr>
                  <?php } ?>
                <?php else: ?>
                  <tr>
                    <td colspan="2">Nenhum aluno encontrado para esta turma.</td>
                  </tr>
                <?php endif; ?>
              </tbody>

              </tbody>
            </table>

            <div class="lf-button-enviar">
              <button type="submit" class="lf-button">Enviar</button>
            </div>
          </form>
        </div>

      </div>

      <div class="box-main" id="tela-03">

        <h1>Lançar Notas</h1>

        <div class="bloco-unidades">
          <div class="botoes-unidade">
            <button type="button" class="botao-unidade ativo" data-unidade="1">1ª Unidade</button>
            <button type="button" class="botao-unidade" data-unidade="2">2ª Unidade</button>
            <button type="button" class="botao-unidade" data-unidade="3">3ª Unidade</button>
            <button type="button" class="botao-unidade" data-unidade="4">4ª Unidade</button>
          </div>

          <form method="post" action="subs/salvarNotas.php">

            <input type="hidden" name="turma" value="<?= htmlspecialchars($turma) ?>">

            <div class="tabela-unidade" id="unidade-1">
              <h2>Notas da 1ª Unidade</h2>
              <table class="tabela-notas">
                <thead>
                  <tr>
                    <th>Aluno</th>
                    <th>N1 (Atividades)</th>
                    <th>N2 (Prova)</th>
                    <th>Média</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($alunos as $aluno) {
                    $id = $aluno['id'];
                    $nome = htmlspecialchars($aluno['nome']);

                    $n1 = $notas[1][$id]['n1'] ?? '';
                    $n2 = $notas[1][$id]['n2'] ?? '';
                    $media = $notas[1][$id]['media'] ?? '';

                  ?>
                    <tr>
                      <td><?= $nome ?></td>
                      <td><input type="number" name="notas[1][<?= $id ?>][n1]" min="0" max="10" step="0.1" value="<?= $n1 ?>"></td>
                      <td><input type="number" name="notas[1][<?= $id ?>][n2]" min="0" max="10" step="0.1" value="<?= $n2 ?>"></td>
                      <td><?= $media ?></td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>

            <div class="tabela-unidade" id="unidade-2">
              <h2>Notas da 2ª Unidade</h2>
              <table class="tabela-notas">
                <thead>
                  <tr>
                    <th>Aluno</th>
                    <th>N1 (Atividades)</th>
                    <th>N2 (Prova)</th>
                    <th>Média</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($alunos as $aluno) {
                    $id = $aluno['id'];
                    $nome = htmlspecialchars($aluno['nome']);

                    $n1 = $notas[2][$id]['n1'] ?? '';
                    $n2 = $notas[2][$id]['n2'] ?? '';
                    $media = $notas[2][$id]['media'] ?? '';
                  ?>
                    <tr>
                      <td><?= $nome ?></td>
                      <td><input type="number" name="notas[2][<?= $id ?>][n1]" min="0" max="10" step="0.1" value="<?= $n1 ?>"></td>
                      <td><input type="number" name="notas[2][<?= $id ?>][n2]" min="0" max="10" step="0.1" value="<?= $n2 ?>"></td>
                      <td><?= $media ?></td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>

            <div class="tabela-unidade" id="unidade-3">
              <h2>Notas da 3ª Unidade</h2>
              <table class="tabela-notas">
                <thead>
                  <tr>
                    <th>Aluno</th>
                    <th>N1 (Atividades)</th>
                    <th>N2 (Prova)</th>
                    <th>Média</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($alunos as $aluno) {
                    $id = $aluno['id'];
                    $nome = htmlspecialchars($aluno['nome']);

                    $n1 = $notas[3][$id]['n1'] ?? '';
                    $n2 = $notas[3][$id]['n2'] ?? '';
                    $media = $notas[3][$id]['media'] ?? '';
                  ?>
                    <tr>
                      <td><?= $nome ?></td>
                      <td><input type="number" name="notas[3][<?= $id ?>][n1]" min="0" max="10" step="0.1" value="<?= $n1 ?>"></td>
                      <td><input type="number" name="notas[3][<?= $id ?>][n2]" min="0" max="10" step="0.1" value="<?= $n2 ?>"></td>
                      <td><?= $media ?></td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>

            <div class="tabela-unidade" id="unidade-4">
              <h2>Notas da 4ª Unidade</h2>
              <table class="tabela-notas">
                <thead>
                  <tr>
                    <th>Aluno</th>
                    <th>N1 (Atividades)</th>
                    <th>N2 (Prova)</th>
                    <th>Média</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($alunos as $aluno) {
                    $id = $aluno['id'];
                    $nome = htmlspecialchars($aluno['nome']);

                    $n1 = $notas[4][$id]['n1'] ?? '';
                    $n2 = $notas[4][$id]['n2'] ?? '';
                    $media = $notas[4][$id]['media'] ?? '';
                  ?>
                    <tr>
                      <td><?= $nome ?></td>
                      <td><input type="number" name="notas[4][<?= $id ?>][n1]" min="0" max="10" step="0.1" value="<?= $n1 ?>"></td>
                      <td><input type="number" name="notas[4][<?= $id ?>][n2]" min="0" max="10" step="0.1" value="<?= $n2 ?>"></td>
                      <td><?= $media ?></td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>


            <div class="botao-enviar">
              <button type="submit">Salvar Notas</button>
            </div>
          </form>
        </div>


      </div>

    </main>
  </div>
</body>
<script src="scripts/dashboard.js"></script>


</html>