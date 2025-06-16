-- 1. Professores (cada professor ensina em uma turma)
CREATE TABLE professores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL  -- senha deve ser armazenada como hash
);

-- 2. Turmas (cada turma possui um único professor)
CREATE TABLE turmas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL,
    professor_id INT UNIQUE,  -- cada professor só ensina uma turma
    FOREIGN KEY (professor_id) REFERENCES professores(id)
);

-- 3. Alunos (cada aluno pertence a uma única turma)
CREATE TABLE alunos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    turma_id INT,
    FOREIGN KEY (turma_id) REFERENCES turmas(id)
);

-- 4. Responsáveis (cada responsável está vinculado a apenas um aluno)
CREATE TABLE responsaveis (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,  -- armazenar hash de senha
    aluno_id INT UNIQUE,  -- garante que cada responsável só tenha um aluno
    FOREIGN KEY (aluno_id) REFERENCES alunos(id)
);

-- 5. Boletins (professores lançam as notas dos alunos)
CREATE TABLE boletins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    aluno_id INT NOT NULL,
    turma_id INT NOT NULL,
    professor_id INT NOT NULL,
    tipo_avaliacao ENUM('prova', 'trabalho', 'atividade') NOT NULL,
    nota DECIMAL(4,2) NOT NULL,
    data_avaliacao DATE NOT NULL,
    
    FOREIGN KEY (aluno_id) REFERENCES alunos(id),
    FOREIGN KEY (turma_id) REFERENCES turmas(id),
    FOREIGN KEY (professor_id) REFERENCES professores(id)
);

-- 6. Calendário (professores registram eventos e presença dos alunos)
CREATE TABLE calendario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    aluno_id INT NOT NULL,
    turma_id INT NOT NULL,
    professor_id INT NOT NULL,
    data_evento DATE NOT NULL,
    tipo_evento ENUM('prova', 'trabalho', 'presenca', 'falta') NOT NULL,
    presenca BOOLEAN,
    observacao TEXT,

    FOREIGN KEY (aluno_id) REFERENCES alunos(id),
    FOREIGN KEY (turma_id) REFERENCES turmas(id),
    FOREIGN KEY (professor_id) REFERENCES professores(id)
);
