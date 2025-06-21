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
        <a class="button-enviar" data-target="tela-04">Relatório</a>

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
                <input type="text" name="deficiencia" style="background-color: #ccc" id="texto-deficiencia" placeholder="Se houver deficiência, informe-a" disabled/>
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
        <form class="main-header">
          <div class="main-header-row">
            <h4>Adicione um novo aviso:</h4>
            <button type="submit" class="button-enviar-laranja">Enviar</button>
          </div>
          <input type="text" placeholder="Título" class="main-header-title-input" />
          <textarea placeholder="Descrição" rows="2" class="main-header-desc-textarea"></textarea>
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
        <h2>relatórios</h2>
      </div>

    </main>
  </div>
</body>
<script src="scripts/dashboard.js"></script>

</html>