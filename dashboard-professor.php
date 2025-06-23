<?php
session_start();

if ((!isset($_SESSION["email"]) == true)) {
  header("Location: login.php");
} else {
  $logado = $_SESSION["email"];
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
  <header>
    <img src="img/logotexto.png" alt="" />
    <h3>Dashboard Professor</h3>
    <a
      href="perfil-professor.html"
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
      <p class="sidebar-title">Selecione a Turma</p>
      <select
        class="sidebar-dropdown"
        name="aluno-selecionado"
        id="select-alunos">
        <option value="id-01" selected>1º Ano A</option>
        <option value="id-02">1º Ano B</option>
        <option value="id-03">3º Ano A</option>
        <option value="id-04">3º Ano A</option>
      </select>
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

      <div class="box-main" id="tela-02">
        <!-- html-lancar-frequencia -->
        <div class="lf-container">
          <h1 class="lf-title">Lançar Frequência</h1>

          <h3 class="lf-subtitle">Selecione a data</h3>
          <input type="date" class="lf-input-date" name="" id="" />

          <table class="lf-tabela">
            <thead>
              <tr class="lf-header-tabela">
                <th>Aluno</th>
                <th>Número de Faltas</th>
                <th>Status presença</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>João da Silva</td>
                <td>2</td>
                <td>
                  <input
                    type="checkbox"
                    name="presenca_joao"
                    class="lf-checkbox" />
                </td>
              </tr>
              <tr>
                <td>Maria Oliveira</td>
                <td>0</td>
                <td>
                  <input
                    type="checkbox"
                    name="presenca_maria"
                    class="lf-checkbox" />
                </td>
              </tr>
              <tr>
                <td>Carlos Souza</td>
                <td>1</td>
                <td>
                  <input
                    type="checkbox"
                    name="presenca_carlos"
                    class="lf-checkbox" />
                </td>
              </tr>
              <tr>
                <td>Fernanda Lima</td>
                <td>3</td>
                <td>
                  <input
                    type="checkbox"
                    name="presenca_fernanda"
                    class="lf-checkbox" />
                </td>
              </tr>
              <tr>
                <td>Lucas Pereira</td>
                <td>0</td>
                <td>
                  <input
                    type="checkbox"
                    name="presenca_lucas"
                    class="lf-checkbox" />
                </td>
              </tr>
              <tr>
                <td>Luciana Souza</td>
                <td>5</td>
                <td>
                  <input
                    type="checkbox"
                    name="presenca_luciana"
                    class="lf-checkbox" />
                </td>
              </tr>
              <tr>
                <td>Eduarda Elvira</td>
                <td>3</td>
                <td>
                  <input
                    type="checkbox"
                    name="presenca_eduarda"
                    class="lf-checkbox" />
                </td>
              </tr>
              <tr>
                <td>Fábio Alves</td>
                <td>4</td>
                <td>
                  <input
                    type="checkbox"
                    name="presenca_fabio"
                    class="lf-checkbox" />
                </td>
              </tr>
              <tr>
                <td>Carlos Henrique</td>
                <td>2</td>
                <td>
                  <input
                    type="checkbox"
                    name="presenca_carlos"
                    class="lf-checkbox" />
                </td>
              </tr>
              <tr>
                <td>Abrivaldo Pereira</td>
                <td>0</td>
                <td>
                  <input
                    type="checkbox"
                    name="presenca_abrivaldo"
                    class="lf-checkbox" />
                </td>
              </tr>
              <tr>
                <td>Paulo Oliveira</td>
                <td>3</td>
                <td>
                  <input
                    type="checkbox"
                    name="presenca_paulo"
                    class="lf-checkbox" />
                </td>
              </tr>
            </tbody>
          </table>

          <div class="lf-button-enviar">
            <button class="lf-button">Enviar</button>
          </div>
        </div>
      </div>

      <div class="box-main" id="tela-03">
        <h1>Input boletim professores</h1>
        <div class="container2">
          <table>
            <thead>
              <tr>
                <th>Aluno</th>
                <th>Notas</th>
                <th>
                  <select class="unidade-select">
                    <option value="id-01" selected>I unidade</option>
                    <option value="id-02">II unidade</option>
                    <option value="id-03">III unidade</option>
                    <option value="id-04">IV unidade</option>
                  </select>
                </th>
            </thead>
            <tbody>
              <tr>
                <td>João da Silva</td>
                <td><input type="text" id="nota" placeholder="Digite de 0 a 10"
                    oninput="this.value = this.value.replace(/[^0-9.]/g, ''); 
                if (this.value.split('.').length > 2) this.value = this.value.replace(/\.+$/, '');
                if (parseFloat(this.value) > 10) this.value = '10';">
                </td>
                <td></td>

              </tr>
              <tr>
                <td>Maria Oliveira</td>
                <td><input type="text" id="nota" placeholder="Digite de 0 a 10"
                    oninput="this.value = this.value.replace(/[^0-9.]/g, ''); 
                if (this.value.split('.').length > 2) this.value = this.value.replace(/\.+$/, '');
                if (parseFloat(this.value) > 10) this.value = '10';">
                </td>
                <td></td>

              </tr>
              <tr>
                <td>Carlos Souza</td>
                <td><input type="text" id="nota" placeholder="Digite de 0 a 10"
                    oninput="this.value = this.value.replace(/[^0-9.]/g, ''); 
                if (this.value.split('.').length > 2) this.value = this.value.replace(/\.+$/, '');
                if (parseFloat(this.value) > 10) this.value = '10';">
                </td>
                <td></td>
              </tr>
              <tr>
                <td>Fernanda Lima</td>
                <td><input type="text" id="nota" placeholder="Digite de 0 a 10"
                    oninput="this.value = this.value.replace(/[^0-9.]/g, ''); 
                if (this.value.split('.').length > 2) this.value = this.value.replace(/\.+$/, '');
                if (parseFloat(this.value) > 10) this.value = '10';">
                </td>
                <td></td>
              </tr>
              <tr>
                <td>Lucas Pereira</td>
                <td><input type="text" id="nota" placeholder="Digite de 0 a 10"
                    oninput="this.value = this.value.replace(/[^0-9.]/g, ''); 
                if (this.value.split('.').length > 2) this.value = this.value.replace(/\.+$/, '');
                if (parseFloat(this.value) > 10) this.value = '10';"></td>
                <td></td>
              </tr>
              <tr>
                <td>Luciana Souza </td>
                <td><input type="text" id="nota" placeholder="Digite de 0 a 10"
                    oninput="this.value = this.value.replace(/[^0-9.]/g, ''); 
                if (this.value.split('.').length > 2) this.value = this.value.replace(/\.+$/, '');
                if (parseFloat(this.value) > 10) this.value = '10';">
                </td>
                <td></td>
              </tr>
              <tr>
                <td>Eduarda Elvira</td>
                <td><input type="text" id="nota" placeholder="Digite de 0 a 10"
                    oninput="this.value = this.value.replace(/[^0-9.]/g, ''); 
                if (this.value.split('.').length > 2) this.value = this.value.replace(/\.+$/, '');
                if (parseFloat(this.value) > 10) this.value = '10';">
                </td>
                <td></td>
              </tr>
              <tr>
                <td>Fábio Alves</td>
                <td><input type="text" id="nota" placeholder="Digite de 0 a 10"
                    oninput="this.value = this.value.replace(/[^0-9.]/g, ''); 
                if (this.value.split('.').length > 2) this.value = this.value.replace(/\.+$/, '');
                if (parseFloat(this.value) > 10) this.value = '10';">
                </td>
                <td></td>
              </tr>
              <tr>
                <td>Carlos Henrique</td>
                <td><input type="text" id="nota" placeholder="Digite de 0 a 10"
                    oninput="this.value = this.value.replace(/[^0-9.]/g, ''); 
                if (this.value.split('.').length > 2) this.value = this.value.replace(/\.+$/, '');
                if (parseFloat(this.value) > 10) this.value = '10';">
                </td>
                <td></td>
              </tr>
              <tr>
                <td>Abrivaldo Pereira</td>
                <td><input type="text" id="nota" placeholder="Digite de 0 a 10"
                    oninput="this.value = this.value.replace(/[^0-9.]/g, ''); 
                if (this.value.split('.').length > 2) this.value = this.value.replace(/\.+$/, '');
                if (parseFloat(this.value) > 10) this.value = '10';">
                </td>
                <td></td>
              </tr>
              <tr>
                <td>Paulo Oliveira</td>
                <td><input type="text" id="nota" placeholder="Digite de 0 a 10"
                    oninput="this.value = this.value.replace(/[^0-9.]/g, ''); 
                if (this.value.split('.').length > 2) this.value = this.value.replace(/\.+$/, '');
                if (parseFloat(this.value) > 10) this.value = '10';">
                </td>
                <td></td>
              </tr>
            </tbody>
          </table>

          <div class="botao-enviar">
            <button>Enviar</button>
          </div>
        </div>
      </div>
    </main>
  </div>
</body>
<script src="scripts/dashboard.js"></script>

</html>