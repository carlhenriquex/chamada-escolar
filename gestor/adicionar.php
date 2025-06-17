<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="css-gestor/adicionar.css">
  <title>Cadastro - Chamada Escolar</title>

</head>

<body>

  <div class="container">

    <div class="form-box">
      <h2>Cadastro de novo professor</h2>
      <form action="cadastro_professor.php" method="POST">
        <label for="nome_prof">Nome completo:</label>
        <input type="text" id="nome_prof" name="nome" required>

        <label for="email_prof">Email:</label>
        <input type="email" id="email_prof" name="email" required>

        <label for="senha_prof">Senha:</label>
        <input type="password" id="senha_prof" name="senha" required>

        <label for="materia">Matéria:</label>
        <input type="text" id="materia" name="materia" required>

        <button type="submit" name="submit-prof">Cadastrar Professor</button>
      </form>
    </div>

    <div class="form-box">
      <h2>Cadastro de novo responsável</h2>
      <form action="cadastro_responsavel.php" method="POST">
        <label for="nome_resp">Nome completo:</label>
        <input type="text" id="nome_resp" name="nome" required>

        <label for="email_resp">Email:</label>
        <input type="email" id="email_resp" name="email" required>

        <label for="senha_resp">Senha:</label>
        <input type="password" id="senha_resp" name="senha" required>

        <label for="aluno">Nome do Aluno:</label>
        <input type="text" id="aluno" name="aluno" required>

        <button type="submit" name="submit-resp">Cadastrar Responsável</button>
      </form>
    </div>

  </div>

</body>
</html>
