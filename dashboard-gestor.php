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
  <title>Dashboard Gestor</title>

  <link rel="stylesheet" href="css/main-gestor.css" />

</head>

<body>
  <header>
    <img src="img/logotexto.png" alt="" />
    <h3>Dashboard Gestor</h3>
    <a href="perfil.html" class="perfil-desktop" data-target="tela-04">
      Perfil
      <span class="perfil-icon" aria-hidden="true">
        <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
          <circle cx="10" cy="6.5" r="4" fill="#fff" />
          <path d="M3 17c0-2.7614 3.134-5 7-5s7 2.2386 7 5" stroke-linecap="round" fill="#fff" />
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
      <div class="sidebar-buttons">
        <a class="button-enviar" data-target="tela-01">Cadastro de Aluno</a>
        <a class="button-enviar" data-target="tela-02">Cadastro de Professor</a>
        <a class="button-enviar" data-target="tela-03">Avisos</a>
        <a class="button-enviar" data-target="tela-04">Gestão de usuários</a>
        <a class="button-enviar" style="background-color: red;" href="subs/sair.php">Sair</a>

        <a href="perfil.html" class="button-enviar perfil-mobile">
          Perfil
          <span class="perfil-icon" aria-hidden="true">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
              <circle cx="10" cy="6.5" r="4" fill="#fff" />
              <path d="M3 17c0-2.7614 3.134-5 7-5s7 2.2386 7 5" stroke-linecap="round" fill="#fff" />
            </svg>
          </span>
        </a>
      </div>
    </aside>

    <main>
      <!-- canvas do cadastro alunos/responsavel -->
      <div class="box-main" id="tela-01">
        <div class="main-header">
          <form class="container-direito" method="post" action="subs/cadastro-aluno.php">
            <div class="row form-icon">
              <img class="icon-estudante" src="img/icon_estudante.png" class="logo-img" alt="ícone estudante" />
            </div>

            <div class="row">
              <h3>Dados do aluno(a)</h3>
            </div>

            <div class="row">
              <div class="input-group">
                <img src="img/usuario.png" alt="Ícone Usuário" />
                <input type="text" name="nome" placeholder="Nome Completo" required />
              </div>
            </div>

            <div class="row">
              <div class="input-group">
                <input type="date" name="nascimento" placeholder="Nascimento" required />
              </div>

              <div class="input-group">
                <input type="text" name="rg" placeholder="RG" required />
              </div>

              <div class="input-group">
                <input type="text" name="cpf" placeholder="CPF" required />
              </div>
            </div>

            <div class="row">
              <div class="input-group">
                <select name="sexo" required>
                  <option value="" disabled selected>Sexo</option>
                  <option value="Masculino">Masculino</option>
                  <option value="Feminino">Feminino</option>
                  <option value="Prefiro não informar">
                    Prefiro não informar
                  </option>
                </select>
              </div>

              <div class="input-group">
                <select name="raca" required>
                  <option value="" disabled selected>Cor/raça</option>
                  <option value="Branco">Branco</option>
                  <option value="Preto">Preto</option>
                  <option value="Pardo">Pardo</option>
                  <option value="Amarelo">Amarelo</option>
                  <option value="Indídena">Indídena</option>
                </select>
              </div>

              <div class="input-group">
                <select name="sangue" required>
                  <option value="" disabled selected>Tipo Sanguíneo</option>
                  <option value="A+">A+</option>
                  <option value="A-">A-</option>
                  <option value="B+">B+</option>
                  <option value="B-">B-</option>
                  <option value="AB+">AB+</option>
                  <option value="AB-">AB-</option>
                  <option value="O+">O+</option>
                  <option value="O-">O-</option>
                </select>
              </div>
            </div>

            <div class="row">
              <div class="input-group">
                <input type="text" name="nacionalidade" placeholder="Nacionalidade" required />
              </div>

              <div class="input-group">
                <input type="text" id="naturalidade" name="naturalidade" placeholder="Naturalidade" required />
              </div>

              <div class="input-group">
                <select name="turma" required>
                  <option value="" disabled selected>Turma</option>
                  <option value="6º ano">6º ano</option>
                  <option value="7º ano">7º ano</option>
                  <option value="8º ano">8º ano</option>
                  <option value="9º ano">9º ano</option>
                </select>
              </div>
            </div>

            <div class="row">
              <div class="input-deficiencia">
                <h4>Possui alguma deficiência?</h4>
                <input type="checkbox" name="" onclick="gerirInputDeDeficiencia(this.checked)" />
              </div>
              <div class="input-group">
                <input type="text" name="deficiencia" style="background-color: #ccc" id="texto-deficiencia" placeholder="Se houver deficiência, informe-a" disabled />
              </div>
            </div>

            <div class="row">
              <div class="input-deficiencia">
                <h4>Responsável já cadastrado?</h4>
                <input type="checkbox" onclick="gerirSeletorDoResponsavel(this.checked)" />
              </div>
              <div class="input-group">
                <input type="text" name="responsavel" style="background-color: #ccc" id="seletor-responsavel" placeholder="Se houver responsável, informe o e-mail" disabled>

                </input>
              </div>
            </div>

            <div class="section-responsaveis" id="section-responsaveis">
              <div class="row">
                <h3>Dados do Responsável</h3>
              </div>

              <div class="row">
                <div class="input-group">
                  <img src="img/usuario.png" alt="Ícone Usuário" />
                  <input type="text" name="nome_responsavel" placeholder="Nome Completo" />
                </div>
              </div>

              <div class="row">
                <div class="input-group">
                  <input type="text" name="rg_responsavel" placeholder="RG" />
                </div>

                <div class="input-group">
                  <input type="text" name="cpf_responsavel" placeholder="CPF" />
                </div>

                <div class="input-group">
                  <select name="parentesto" required>
                    <option value="" disabled selected>
                      Grau de parentesco
                    </option>
                    <option value="Pai">Pai</option>
                    <option value="Mãe">Mãe</option>
                    <option value="Avô">Avô</option>
                    <option value="Avó">Avó</option>
                    <option value="Tio">Tio</option>
                    <option value="Tia">Tia</option>
                  </select>
                </div>
              </div>

              <div class="row">
                <div class="input-group">
                  <input type="text" name="rua" placeholder="Nome da rua" required />
                </div>

                <div class="input-group">
                  <input type="text" name="numero" placeholder="Número" required />
                </div>

                <div class="input-group">
                  <input type="text" name="bairro" placeholder="Bairro" required />
                </div>
              </div>

              <div class="row">
                <div class="input-group">
                  <input type="text" name="cidade" placeholder="Cidade" required />
                </div>

                <div class="input-group">
                  <input type="text" name="complemento" placeholder="Complemento" required />
                </div>

                <div class="input-group">
                  <input type="text" name="cep" placeholder="CEP" required />
                </div>
              </div>

              <div class="row">
                <div class="input-group">
                  <input type="text" name="telefone" placeholder="Telefone" required />
                </div>

                <div class="input-group">
                  <input type="text" name="email" placeholder="Email" required />
                </div>
              </div>

              <div class="row">
                <div class="input-group">
                  <img src="img/senha.png" alt="Ícone Senha" />
                  <input type="password" name="senha1" placeholder="Senha" required />
                </div>

                <div class="input-group">
                  <img src="img/senha.png" alt="Ícone Confirmar Senha" />
                  <input type="password" name="senha2" placeholder="Repetir senha" required />
                </div>
              </div>
            </div>

            <div class="row">
              <button type="submit" name="submit" class="button-enviar-laranja">
                Cadastrar
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- canvas do cadastro professor -->
      <div class="box-main" id="tela-02">

        <div class="main-header">

          <form method="post" action="subs/cadastro-professor.php" class="container-direito">
            <div class="row form-icon">
              <img class="icon-prof" src="img/icon_professor.png" class="logo-img" alt="ícone professor" />
            </div>

            <div class="row">
              <h3>Cadastro Professor(a)</h3>
            </div>

            <div class="row">
              <div class="input-group">
                <img src="img/usuario.png" alt="Ícone Usuário" />
                <input type="text" name="nome" placeholder="Nome Completo" required />
              </div>
            </div>

            <div class="row">
              <div class="input-group">
                <input type="date" name="nascimento" placeholder="Nascimento" required />
              </div>

              <div class="input-group">
                <input type="text" name="rg" placeholder="RG" required />
              </div>

              <div class="input-group">
                <input type="text" name="cpf" placeholder="CPF" required />
              </div>
            </div>

            <div class="row">
              <div class="input-group">
                <select name="sexo" required>
                  <option value="" disabled selected>Sexo</option>
                  <option value="Masculino">Masculino</option>
                  <option value="Feminino">Feminino</option>
                  <option value="Prefiro não informar">
                    Prefiro não informar
                  </option>
                </select>
              </div>

              <div class="input-group">
                <select name="raca" required>
                  <option value="" disabled selected>Cor/raça</option>
                  <option value="Branco">Branco</option>
                  <option value="Preto">Preto</option>
                  <option value="Pardo">Pardo</option>
                  <option value="Amarelo">Amarelo</option>
                  <option value="Indídena">Indídena</option>
                </select>
              </div>

              <div class="input-group">
                <select name="sangue" required>
                  <option value="" disabled selected>Tipo Sanguíneo</option>
                  <option value="A+">A+</option>
                  <option value="A-">A-</option>
                  <option value="B+">B+</option>
                  <option value="B-">B-</option>
                  <option value="AB+">AB+</option>
                  <option value="AB-">AB-</option>
                  <option value="O+">O+</option>
                  <option value="O-">O-</option>
                </select>
              </div>
            </div>

            <div class="row">
              <div class="input-group">
                <input type="text" name="formacao" placeholder="Formação acadêmica" required />
              </div>

              <div class="input-group">
                <input type="text" name="disciplina" placeholder="Disciplina" required />
              </div>

              <div class="input-group">
                <select name="turma" required>
                  <option value="" disabled selected>Turma</option>
                  <option value="6º ano">6º ano</option>
                  <option value="7º ano">7º ano</option>
                  <option value="8º ano">8º ano</option>
                  <option value="9º ano">9º ano</option>
                </select>
              </div>
            </div>

            <div class="row">
              <div class="input-group">
                <input type="text" name="rua" placeholder="Nome da Rua" />
              </div>

              <div class="input-group">
                <input type="text" name="numero" placeholder="Número" />
              </div>

              <div class="input-group">
                <input type="text" name="bairro" placeholder="Bairro" />
              </div>
            </div>

            <div class="row">
              <div class="input-group">
                <input type="text" name="cidade" placeholder="Cidade" />
              </div>

              <div class="input-group">
                <input type="text" name="complemento" placeholder="Complemento" />
              </div>

              <div class="input-group">
                <input type="text" name="cep" placeholder="CEP" />
              </div>
            </div>

            <div class="row">
              <div class="input-group">
                <input type="text" name="telefone" placeholder="Telefone" required />
              </div>

              <div class="input-group">
                <input type="text" name="email" placeholder="Email" required />
              </div>
            </div>

            <div class="row">
              <div class="input-group">
                <img src="img/senha.png" alt="Ícone Senha" />
                <input type="password" name="senha1" placeholder="Senha" required />
              </div>

              <div class="input-group">
                <img src="img/senha.png" alt="Ícone Confirmar Senha" />
                <input type="password" name="senha2" placeholder="Repetir senha" required />
              </div>
            </div>

            <div class="row">
              <button type="submit" name="submit" class="button-enviar-laranja">
                Cadastrar
              </button>
            </div>
          </form>

        </div>
      </div>

      <!-- canvas do avisos -->
      <div class="box-main" id="tela-03">
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
            <div class="feed-item">
              <p><strong>Título do aviso 1</strong></p>
              <div class="repo-card">
                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                Voluptate incidunt neque pariatur accusamus aliquam a laboriosam
                vitae recusandae vero, modi corrupti explicabo deleniti tenetur
                facilis at, veritatis, est nulla accusantium!
              </div>
              <small>4 horas atrás</small>
            </div>
            <div class="feed-item">
              <p><strong>Título do aviso 2</strong></p>
              <div class="repo-card">
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Quidem
                quam minima eius tempora at libero fugiat corrupti magni tempore
                obcaecati? Repudiandae omnis dolorum velit illum veritatis.
                Dignissimos atque eveniet assumenda.
              </div>
              <small>2 dias atrás</small>
            </div>
            <div class="feed-item">
              <p><strong>Título do aviso 3</strong></p>
              <div class="repo-card">
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Quidem
                quam minima eius tempora at libero fugiat corrupti magni tempore
                obcaecati? Repudiandae omnis dolorum velit illum veritatis.
                Dignissimos atque eveniet assumenda.
              </div>
              <small>2 dias atrás</small>
            </div>
            <div class="feed-item">
              <p><strong>Título do aviso 4</strong></p>
              <div class="repo-card">
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Quidem
                quam minima eius tempora at libero fugiat corrupti magni tempore
                obcaecati? Repudiandae omnis dolorum velit illum veritatis.
                Dignissimos atque eveniet assumenda.
              </div>
              <small>2 dias atrás</small>
            </div>
            <div class="feed-item">
              <p><strong>Título do aviso 5</strong></p>
              <div class="repo-card">
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Quidem
                quam minima eius tempora at libero fugiat corrupti magni tempore
                obcaecati? Repudiandae omnis dolorum velit illum veritatis.
                Dignissimos atque eveniet assumenda.
              </div>
              <small>4 dias atrás</small>
            </div>
          </div>
        </section>
      </div>

      <!-- canvas do relatório -->
      <div class="box-main" id="tela-04">
        <div class="container-admin">
          <h1 class="mb-4">gestão de usuários</h1>

          <div class="tab-nav">
            <button class="tab-link active" data-target="professores">Professores</button>
            <button class="tab-link" data-target="alunos">Alunos</button>
            <button class="tab-link" data-target="responsaveis">Responsáveis</button>
            <button class="tab-link" data-target="avisos">Avisos</button>
          </div>

          <div class="tab-content active" id="professores">
            <?php
            include_once("config/connection.php");
            $sql = "SELECT * FROM professores";
            $resultado = $conexao->query($sql);

            if ($resultado->num_rows > 0) {
              echo "<ul class='list-group'>";
              while ($p = $resultado->fetch_assoc()) {
                $id = $p['id'];
                echo "<li class='list-group-item' id='professor-{$id}'>";

                // Dados visíveis
                echo "<div class='dados-visiveis'>";
                echo "<strong>{$p['nome']}</strong> - {$p['email']}";

                echo "<div style='margin-top: 5px;'>";
                echo "<button type='button' onclick=\"toggleEditar('professor-$id')\">Editar</button>";

                echo "<form method='post' action='subs/deletar-professor.php' style='display:inline;' onsubmit=\"return confirm('Deseja remover este professor?');\">";
                echo "<input type='hidden' name='delete_id' value='{$id}'>";
                echo "<button type='submit'>Remover</button>";
                echo "</form>";
                echo "</div>";
                echo "</div>";

                // Formulário de edição embutido
                echo "<form method='post' action='subs/editar-professor.php' id='form-editar-professor-{$id}' style='display:none; margin-top:10px;'>";

                echo "<input type='hidden' name='id' value='{$id}'>";

                // Linha 1 - nome
                echo "<input type='text' name='nome' value='" . htmlspecialchars($p['nome']) . "' placeholder='Nome completo' required>";

                // Linha 2 - nascimento, RG, CPF
                echo "<input type='date' name='nascimento' value='{$p['nascimento']}' required>";
                echo "<input type='text' name='rg' value='{$p['rg']}' placeholder='RG' required>";
                echo "<input type='text' name='cpf' value='{$p['cpf']}' placeholder='CPF' required>";

                // Linha 3 - sexo, raça, tipo sanguíneo
                echo "<select name='sexo' required>";
                foreach (['Masculino', 'Feminino', 'Prefiro não informar'] as $opcao) {
                  $selected = ($p['sexo'] == $opcao) ? 'selected' : '';
                  echo "<option value='$opcao' $selected>$opcao</option>";
                }
                echo "</select>";

                echo "<select name='raca' required>";
                foreach (['Branco', 'Preto', 'Pardo', 'Amarelo', 'Indídena'] as $opcao) {
                  $selected = ($p['raca'] == $opcao) ? 'selected' : '';
                  echo "<option value='$opcao' $selected>$opcao</option>";
                }
                echo "</select>";

                echo "<select name='sangue' required>";
                foreach (['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $opcao) {
                  $selected = ($p['sangue'] == $opcao) ? 'selected' : '';
                  echo "<option value='$opcao' $selected>$opcao</option>";
                }
                echo "</select>";

                // Linha 4 - formação, disciplina, turma
                echo "<input type='text' name='formacao' value='{$p['formacao']}' placeholder='Formação acadêmica' required>";
                echo "<input type='text' name='disciplina' value='{$p['disciplina']}' placeholder='Disciplina' required>";

                echo "<select name='turma' required>";
                foreach (['6º ano', '7º ano', '8º ano', '9º ano'] as $opcao) {
                  $selected = ($p['turma'] == $opcao) ? 'selected' : '';
                  echo "<option value='$opcao' $selected>$opcao</option>";
                }
                echo "</select>";

                // Linha 5 - endereço
                echo "<input type='text' name='rua' value='{$p['rua']}' placeholder='Rua' required>";
                echo "<input type='text' name='numero' value='{$p['numero']}' placeholder='Número' required>";
                echo "<input type='text' name='bairro' value='{$p['bairro']}' placeholder='Bairro' required>";
                echo "<input type='text' name='cidade' value='{$p['cidade']}' placeholder='Cidade' required>";
                echo "<input type='text' name='complemento' value='{$p['complemento']}' placeholder='Complemento'>";
                echo "<input type='text' name='cep' value='{$p['cep']}' placeholder='CEP' required>";

                // Linha 6 - contato
                echo "<input type='text' name='telefone' value='{$p['telefone']}' placeholder='Telefone' required>";
                echo "<input type='email' name='email' value='{$p['email']}' placeholder='Email' required>";

                // Botões
                echo "<button type='submit'>Salvar</button>";
                echo "<button type='button' onclick=\"toggleEditar('$id')\">Cancelar</button>";

                echo "</form>";
                echo "</li>";
              }
              echo "</ul>";
            } else {
              echo "<p>Nenhum professor cadastrado.</p>";
            }
            ?>

          </div>

          <div class="tab-content" id="alunos">
            <?php
            $sql = "SELECT * FROM alunos";
            $resultado = $conexao->query($sql);

            if ($resultado->num_rows > 0) {
              echo "<ul class='list-group'>";
              while ($a = $resultado->fetch_assoc()) {
                $id = $a['id'];
                echo "<li class='list-group-item' id='aluno-{$id}'>";

                // Dados visíveis
                echo "<div class='dados-visiveis'>";
                echo "<strong>{$a['nome']}</strong> - {$a['turma']}";
                echo "<div style='margin-top: 5px;'>";
                echo "<button type='button' onclick=\"toggleEditar('aluno-$id')\">Editar</button>";

                echo "<form method='post' action='subs/deletar-aluno.php' style='display:inline;' onsubmit=\"return confirm('Deseja remover este aluno?');\">";
                echo "<input type='hidden' name='delete_id' value='{$id}'>";
                echo "<button type='submit'>Remover</button>";
                echo "</form>";
                echo "</div>";
                echo "</div>";

                // Formulário de edição embutido

                echo "<form method='post' action='subs/editar-aluno.php' id='form-editar-aluno-{$id}' style='display:none; margin-top:10px;'>";
                echo "<input type='hidden' name='id' value='{$id}'>";

                echo "<input type='text' name='nome' value='{$a['nome']}' placeholder='Nome completo' required>";
                echo "<input type='date' name='nascimento' value='{$a['nascimento']}' required>";
                echo "<input type='text' name='rg' value='{$a['rg']}' placeholder='RG' required>";
                echo "<input type='text' name='cpf' value='{$a['cpf']}' placeholder='CPF' required>";

                echo "<select name='sexo' required>";
                foreach (['Masculino', 'Feminino', 'Prefiro não informar'] as $opcao) {
                  $selected = ($a['sexo'] == $opcao) ? 'selected' : '';
                  echo "<option value='$opcao' $selected>$opcao</option>";
                }
                echo "</select>";

                echo "<select name='raca' required>";
                foreach (['Branco', 'Preto', 'Pardo', 'Amarelo', 'Indídena'] as $opcao) {
                  $selected = ($a['raca'] == $opcao) ? 'selected' : '';
                  echo "<option value='$opcao' $selected>$opcao</option>";
                }
                echo "</select>";

                echo "<select name='sangue' required>";
                foreach (['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $opcao) {
                  $selected = ($a['sangue'] == $opcao) ? 'selected' : '';
                  echo "<option value='$opcao' $selected>$opcao</option>";
                }
                echo "</select>";

                echo "<input type='text' name='nacionalidade' value='{$a['nacionalidade']}' placeholder='Nacionalidade' required>";
                echo "<input type='text' name='naturalidade' value='{$a['naturalidade']}' placeholder='Naturalidade' required>";

                echo "<select name='turma' required>";
                foreach (['6º ano', '7º ano', '8º ano', '9º ano'] as $opcao) {
                  $selected = ($a['turma'] == $opcao) ? 'selected' : '';
                  echo "<option value='$opcao' $selected>$opcao</option>";
                }
                echo "</select>";

                echo "<input type='text' name='deficiencia' value='{$a['deficiencia']}' placeholder='Deficiência (se houver)'>";

                echo "<label>Responsável:</label>";
                echo "<select name='responsavel_id'>";
                echo "<option value=''>Nenhum</option>";
                $res = $conexao->query("SELECT id, nome FROM responsaveis");
                while ($resp = $res->fetch_assoc()) {
                  $selected = ($a['responsavel_id'] == $resp['id']) ? 'selected' : '';
                  echo "<option value='{$resp['id']}' $selected>{$resp['nome']}</option>";
                }
                echo "</select>";

                echo "<button type='submit'>Salvar</button>";
                echo "<button type='button' onclick=\"toggleEditar('{$id}')\">Cancelar</button>";
                echo "</form>";

                echo "</li>";
              }
              echo "</ul>";
            } else {
              echo "<p>Nenhum aluno cadastrado.</p>";
            }
            ?>
          </div>

          <div class="tab-content" id="responsaveis">
            <?php
            $sql = "SELECT * FROM responsaveis";
            $resultado = $conexao->query($sql);
            if ($resultado->num_rows > 0) {
              echo "<ul class='list-group'>";
              while ($r = $resultado->fetch_assoc()) {
                echo "<li class='list-group-item'><strong>" . $r["nome"] . "</strong> - " . $r["email"];

                echo "<form method='post' onsubmit=\"return confirm('Tem certeza que deseja remover este responsável?');\">";
                echo "<input type='hidden' name='delete_id' value='{$r['id']}'>";
                echo "<button type='submit'>Remover</button>";
                echo "</form>";
                echo "</li>";
              }
              echo "</ul>";
            } else {
              echo "<p>Nenhum responsável cadastrado.</p>";
            }
            ?>
          </div>

          <div class="tab-content" id="avisos">
            <?php
            $sql = "SELECT avisos.*, 
              CASE 
                  WHEN autor_tipo = 'gestor' THEN gestores.username
                  WHEN autor_tipo = 'professor' THEN professores.nome 
              END AS autor_nome
              FROM avisos
              LEFT JOIN gestores ON autor_tipo = 'gestor' AND autor_id = gestores.id
              LEFT JOIN professores ON autor_tipo = 'professor' AND autor_id = professores.id
              ORDER BY data_publicacao DESC";
            $resultado = $conexao->query($sql);
            if ($resultado->num_rows > 0) {
              echo "<ul class='list-group'>";
              while ($av = $resultado->fetch_assoc()) {
                echo "<li class='list-group-item'><strong>" . $av["titulo"] . "</strong> - " . $av["descricao"] . "<br><small>Por: " . $av["autor_nome"] . " em " . $av["data_publicacao"] . "</small>";

                echo "<form method='post' onsubmit=\"return confirm('Tem certeza que deseja remover este aviso?');\">";
                echo "<input type='hidden' name='delete_id' value='{$av['id']}'>";
                echo "<button type='submit'>Remover</button>";
                echo "</form>";
                echo "</li>";
              }
              echo "</ul>";
            } else {
              echo "<p>Nenhum aviso publicado.</p>";
            }
            ?>
          </div>
        </div>
      </div>

    </main>
  </div>
</body>
<script src="scripts/dashboard.js"></script>
<script src="scripts/gestaoUsers.js"></script>

</html>