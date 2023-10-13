/* INTEGRANTES - pdvher45_PDV Hermes - INF3GM
Gustavo da Silva Ferreira - 5
Kauê Hugo Almeida Cavalcanti - 12
Marcel Pereira dos Santos - 14
Ricardo Gabriel de Souza Lopes - 22
Vinicius Santos Rocha - 31
 */
CREATE DATABASE IF NOT EXISTS pdvher45_PDV;

USE pdvher45_PDV;

DROP TABLE IF EXISTS itens_pedido;

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

CREATE TABLE
    itens_pedido (
        id INT AUTO_INCREMENT PRIMARY KEY,
        produto_id INT NOT NULL,
        quantidade INT NOT NULL,
        pedido_id INT,
        comercio_id INT,
        FOREIGN KEY (produto_id) REFERENCES produto (id),
        FOREIGN KEY (pedido_id) REFERENCES pedido (id),
        FOREIGN KEY (comercio_id) REFERENCES comercio (id)
    );

INSERT INTO pedido (data_pedido, valor_total, lucro_obtido, responsavel_id, pagamento_id, comercio_id)
VALUES
    ('2023-10-06 12:30:00', 100.00, 30.00, 1, 1, 1),
    ('2023-10-08 14:45:00', 75.50, 22.50, 1, 1, 1),
    ('2023-10-10 10:15:00', 50.25, 15.00, 1, 1, 1);
INSERT INTO pedido (data_pedido, valor_total, lucro_obtido, responsavel_id, pagamento_id, comercio_id)
VALUES
    ('2023-09-15 16:20:00', 120.00, 35.50, 1, 1, 1),
    ('2023-09-22 11:30:00', 90.75, 27.25, 1, 1, 1),
    ('2023-09-28 09:45:00', 60.30, 18.50, 1, 1, 1);
INSERT INTO pedido (data_pedido, valor_total, lucro_obtido, responsavel_id, pagamento_id, comercio_id)
VALUES
    ('2023-10-13 08:00:00', 85.00, 25.50, 1, 1, 1),
    ('2023-10-13 12:30:00', 65.75, 19.25, 1, 1, 1),
    ('2023-10-13 16:15:00', 45.20, 13.75, 1, 1, 1);
    
SELECT * FROM pedido;

