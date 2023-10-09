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

-- Inserções na tabela 'comercio'
INSERT INTO
    comercio (nome, cpf_cnpj)
VALUES
    ('Minha Loja 1', '123.456.789-00'),
    ('Loja do Bairro', '987.654.321-00'),
    ('Comércio de Roupas', '456.789.123-00');

-- Inserções na tabela 'usuario' com diferentes níveis de acesso
INSERT INTO
    usuario (nome, email, senha, nivel_acesso, comercio_id)
VALUES
    (
        'Proprietário 1',
        'proprietario1@email.com',
        'senha123',
        'Proprietário',
        1
    ),
    (
        'Funcionário 1',
        'funcionario1@email.com',
        'senha456',
        'Funcionário',
        1
    ),
    (
        'Funcionário 2',
        'funcionario2@email.com',
        'senha789',
        'Funcionário',
        2
    );

-- Inserções na tabela 'forma_pagamento'
INSERT INTO
    forma_pagamento (tipo)
VALUES
    ('Cartão de Crédito'),
    ('Dinheiro'),
    ('Débito');

-- Inserções na tabela 'produto'
INSERT INTO
    produto (
        nome,
        valor_fabrica,
        valor_venda,
        descricao,
        comercio_id
    )
VALUES
    (
        'Camiseta Branca',
        10.00,
        20.00,
        'Camiseta de algodão branca',
        1
    ),
    (
        'Calça Jeans',
        25.00,
        50.00,
        'Calça jeans azul',
        1
    ),
    (
        'Tênis Esportivo',
        30.00,
        70.00,
        'Tênis esportivo preto',
        2
    );

-- Inserções na tabela 'pedido'
INSERT INTO
    pedido (
        data_pedido,
        valor_total,
        lucro_obtido,
        responsavel_id,
        pagamento_id,
        comercio_id
    )
VALUES
    ('2023-09-05 10:00:00', 100.00, 30.00, 1, 1, 1),
    ('2023-09-06 15:30:00', 75.00, 20.00, 2, 2, 1),
    ('2023-09-07 09:45:00', 120.00, 40.00, 1, 3, 2);

-- Inserções na tabela 'itens_pedido'
INSERT INTO
    itens_pedido (produto_id, quantidade, pedido_id, comercio_id)
VALUES
    (1, 2, 1, 1),
    (2, 1, 1, 1),
    (3, 3, 2, 1);

SELECT
    *
FROM
    pdvher45_PDV.usuario;