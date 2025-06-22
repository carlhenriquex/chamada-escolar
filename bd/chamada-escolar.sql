CREATE TABLE gestores (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL,
  email VARCHAR(50) UNIQUE NOT NULL,
  senha VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE professores (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
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
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE responsaveis (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
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
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE alunos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
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
  FOREIGN KEY (responsavel_id) REFERENCES responsaveis(id) ON DELETE
  SET
    NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE avisos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  titulo VARCHAR(100) NOT NULL,
  descricao TEXT NOT NULL,
  data_publicacao DATETIME DEFAULT CURRENT_TIMESTAMP,
  autor_id INT NOT NULL,
  autor_tipo ENUM('gestor', 'professor') NOT NULL
);