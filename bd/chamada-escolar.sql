CREATE TABLE gestores (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL,
  email VARCHAR(50) UNIQUE NOT NULL,
  senha VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  removido_em DATETIME DEFAULT NULL
);


CREATE TABLE professores (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  foto VARCHAR(255) DEFAULT NULL,
  nascimento DATE NOT NULL,
  rg VARCHAR(20),
  cpf VARCHAR(20),
  sexo VARCHAR(30),
  raca VARCHAR(30),
  tipo_sanguineo VARCHAR(10),
  formacao VARCHAR(100),
  disciplina VARCHAR(100),
  turma VARCHAR(20),
  rua VARCHAR(100),
  numero VARCHAR(10),
  bairro VARCHAR(50),
  cidade VARCHAR(50),
  complemento VARCHAR(100),
  cep VARCHAR(20),
  telefone VARCHAR(20),
  email VARCHAR(100) UNIQUE NOT NULL,
  senha VARCHAR(255) NOT NULL,
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  removido_em DATETIME DEFAULT NULL
);

CREATE TABLE responsaveis (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  foto VARCHAR(255) DEFAULT NULL,
  rg VARCHAR(20),
  cpf VARCHAR(20),
  parentesco VARCHAR(50),
  rua VARCHAR(100),
  numero VARCHAR(10),
  bairro VARCHAR(50),
  cidade VARCHAR(50),
  complemento VARCHAR(100),
  cep VARCHAR(20),
  telefone VARCHAR(20),
  email VARCHAR(100) UNIQUE NOT NULL,
  senha VARCHAR(255) NOT NULL,
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  removido_em DATETIME DEFAULT NULL
);

CREATE TABLE alunos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  foto VARCHAR(255) DEFAULT NULL,
  nascimento DATE NOT NULL,
  rg VARCHAR(20),
  cpf VARCHAR(20),
  sexo VARCHAR(20),
  raca VARCHAR(20),
  tipo_sanguineo VARCHAR(10),
  nacionalidade VARCHAR(50),
  naturalidade VARCHAR(50),
  turma VARCHAR(20),
  deficiencia VARCHAR(100) DEFAULT NULL,
  responsavel_id INT,
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  removido_em DATETIME DEFAULT NULL,
  FOREIGN KEY (responsavel_id) REFERENCES responsaveis(id) ON DELETE
  SET
    NULL
);

CREATE TABLE avisos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  titulo VARCHAR(100) NOT NULL,
  descricao TEXT NOT NULL,
  data_publicacao DATETIME DEFAULT CURRENT_TIMESTAMP,
  autor_id INT NOT NULL,
  autor_tipo ENUM('gestor', 'professor') NOT NULL
);

CREATE TABLE notas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  aluno_id INT NOT NULL,
  disciplina VARCHAR(100) NOT NULL,
  unidade TINYINT NOT NULL CHECK (
    unidade BETWEEN 1
    AND 4
  ),
  n1 DECIMAL(4, 1) NOT NULL,
  n2 DECIMAL(4, 1) NOT NULL,
  media DECIMAL(4, 1) GENERATED ALWAYS AS ((n1 + n2) / 2) STORED,
  data_lancamento TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE (aluno_id, disciplina, unidade),
  FOREIGN KEY (aluno_id) REFERENCES alunos(id) ON DELETE CASCADE
);

CREATE TABLE presencas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  aluno_id INT NOT NULL,
  data_presenca DATE NOT NULL,
  presente BOOLEAN NOT NULL,
  turma VARCHAR(20) NOT NULL,
  disciplina VARCHAR(100) NOT NULL,
  data_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE (aluno_id, data_presenca, turma, disciplina),
  FOREIGN KEY (aluno_id) REFERENCES alunos(id) ON DELETE CASCADE
);