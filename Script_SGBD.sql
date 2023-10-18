/* INTEGRANTES - pdvher45_PDV Hermes - INF3GM
Gustavo da Silva Ferreira - 5
Kauê Hugo Almeida Cavalcanti - 12
Marcel Pereira dos Santos - 14
Ricardo Gabriel de Souza Lopes - 22
Vinicius Santos Rocha - 31
 */
CREATE DATABASE IF NOT EXISTS pdvher45_PDV;

USE pdvher45_PDV;

DROP TABLE IF EXISTS pedido;

DROP TABLE IF EXISTS produto;

DROP TABLE IF EXISTS forma_pagamento;

DROP TABLE IF EXISTS usuario;

DROP TABLE IF EXISTS comercio;

CREATE TABLE
    comercio (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nome VARCHAR(120),
        cpf_cnpj VARCHAR(20)
    );

CREATE TABLE
    usuario (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nome VARCHAR(50),
        email VARCHAR(100),
        senha VARCHAR(100),
        nivel_acesso VARCHAR(50),
        comercio_id INT,
        status VARCHAR(255),
        FOREIGN KEY (comercio_id) REFERENCES comercio (id)
    );

CREATE TABLE
    forma_pagamento (
        id INT AUTO_INCREMENT PRIMARY KEY,
        tipo VARCHAR(50) NOT NULL
    );

CREATE TABLE
    produto (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nome VARCHAR(100) NOT NULL,
        valor_fabrica DECIMAL(10, 2) NOT NULL,
        valor_venda DECIMAL(10, 2) NOT NULL,
        descricao VARCHAR(150),
        imagem VARCHAR(255),
        comercio_id INT,
        status VARCHAR(255),
        FOREIGN KEY (comercio_id) REFERENCES comercio (id)
    );

CREATE TABLE
    pedido (
        id INT AUTO_INCREMENT PRIMARY KEY,
        data_pedido DATETIME NOT NULL,
        valor_total DECIMAL(10, 2) NOT NULL,
        lucro_obtido DECIMAL(10, 2),
        responsavel_id INT NOT NULL,
        pagamento_id INT NOT NULL,
        comercio_id INT NOT NULL,
        FOREIGN KEY (responsavel_id) REFERENCES usuario (id),
        FOREIGN KEY (pagamento_id) REFERENCES forma_pagamento (id),
        FOREIGN KEY (comercio_id) REFERENCES comercio (id)
    );