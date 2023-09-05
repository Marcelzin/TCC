/* INTEGRANTES - PDV Hermes - INF3GM
Gustavo da Silva Ferreira - 5
Kauê Hugo Almeida Cavalcanti - 12
Marcel Pereira dos Santos - 14
Ricardo Gabriel de Souza Lopes - 22
Vinicius Santos Rocha - 31
*/

CREATE DATABASE IF NOT EXISTS PDV;
USE PDV;

DROP TABLE IF EXISTS usuario;
CREATE TABLE usuario (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(50),
  email VARCHAR(100),
  senha VARCHAR(100),
  nivel_acesso VARCHAR(50)
);

DROP TABLE IF EXISTS forma_pagamento;
CREATE TABLE forma_pagamento (
  id INT AUTO_INCREMENT PRIMARY KEY,
  tipo VARCHAR(50) NOT NULL
);

DROP TABLE IF EXISTS produto;
CREATE TABLE produto (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  valor_fabrica DECIMAL(10,2) NOT NULL,
  valor_venda DECIMAL(10,2) NOT NULL,
  descricao VARCHAR(150)
);

DROP TABLE IF EXISTS pedido;
CREATE TABLE pedido (
  id INT AUTO_INCREMENT PRIMARY KEY,
  data_pedido DATETIME NOT NULL,
  valor_total DECIMAL(10,2) NOT NULL,
  lucro_obtido DECIMAL(10,2),
  responsavel_id INT NOT NULL,
  pagamento_id INT NOT NULL,
  FOREIGN KEY (responsavel_id) REFERENCES usuario (id),
  FOREIGN KEY (pagamento_id) REFERENCES forma_pagamento (id)
);

DROP TABLE IF EXISTS itens_pedido;
CREATE TABLE itens_pedido (
  id INT AUTO_INCREMENT PRIMARY KEY,
  produto_id INT NOT NULL,
  quantidade INT NOT NULL,
  pedido_id INT,
  FOREIGN KEY (produto_id) REFERENCES produto (id),
  FOREIGN KEY (pedido_id) REFERENCES pedido (id)
);

-- Populando a tabela 'usuario'
INSERT INTO usuario (nome, email, senha, nivel_acesso)
VALUES
  ('João', 'joao@example.com', 'senha123', 'Proprietário'),
  ('Maria', 'maria@example.com', 'senha456', 'Funcionario'),
  ('Pedro', 'pedro@example.com', 'senha789', 'Kung-fu');

-- Populando a tabela 'forma_pagamento'
INSERT INTO forma_pagamento (tipo)
VALUES
  ('Dinheiro'),
  ('Cartão de Crédito'),
  ('Cartão de Débito');

-- Populando a tabela 'produto'
INSERT INTO produto (nome, valor_fabrica, valor_venda, descricao)
VALUES
  ('Celular', 500.00, 800.00, 'Smartphone'),
  ('Camiseta', 10.00, 25.00, 'Camiseta básica'),
  ('Arroz', 5.00, 10.00, 'Arroz branco');

-- Populando a tabela 'pedido'
INSERT INTO pedido (data_pedido, valor_total, lucro_obtido, responsavel_id, pagamento_id)
VALUES
  ('2023-06-01', 200.00, 50.00, 1, 1),
  ('2023-06-02', 50.00, 10.00, 2, 2),
  ('2023-06-03', 100.00, 20.00, 3, 3);

-- Populando a tabela 'itens_pedido'
INSERT INTO itens_pedido (produto_id, quantidade, pedido_id)
VALUES
  (1, 2, 1),
  (2, 5, 2),
  (3, 3, 3);

SELECT * FROM pdv.produto;