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
    username VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    aluno_nome VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO gestores (username, email, senha)
VALUES ('Carlos Henrique', 'carl@gmail.com', '123');

INSERT INTO professores (username, email, senha, materia)
VALUES ('Eduarda Elvira', 'eduarda@gmail.com', '123', 'Matematica');

INSERT INTO responsaveis (username, email, senha, aluno_nome)
VALUES ('Luciana Souza', 'luciana@gmail.com', '123', 'Lucas Souza');