create Database POP_BD;
use POP_BD;

CREATE TABLE bandas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL
);

CREATE TABLE integrantes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    banda_id INT NOT NULL,
    FOREIGN KEY (banda_id) REFERENCES bandas(id)
);

CREATE TABLE musicas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    banda_id INT NOT NULL,
    FOREIGN KEY (banda_id) REFERENCES bandas(id)
);

Create TABLE Logins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(255) NOT NULL,
    senha VARCHAR(255) NOT NULL
)