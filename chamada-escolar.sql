CREATE TABLE gestores (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL,
  email VARCHAR(50) UNIQUE NOT NULL,
  senha VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE professores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    materia VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
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