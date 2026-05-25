-- Cria o banco de dados
CREATE DATABASE IF NOT EXISTS controle_financeiro;

-- Informa ao sistema que vamos usar este banco
USE controle_financeiro;

-- 1. Criação da tabela de Usuários
CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    telefone VARCHAR(20),
    senha VARCHAR(255) NOT NULL
);

-- 2. Criação da tabela de Categorias
CREATE TABLE categorias (
    id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    descricao VARCHAR(100) NOT NULL,
    usuario_id INT,
    CONSTRAINT fk_categorias_usuario FOREIGN KEY (usuario_id) 
        REFERENCES usuarios(id_usuario)
);

-- 3. Criação da tabela de Metas
CREATE TABLE metas (
    id_meta INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    valor_meta DECIMAL(10,2) NOT NULL,
    valor_atual DECIMAL(10,2) DEFAULT 0.00,
    prazo DATE,
    usuario_id INT,
    CONSTRAINT fk_metas_usuario FOREIGN KEY (usuario_id) 
        REFERENCES usuarios(id_usuario)
);

-- 4. Criação da tabela de Movimentações
CREATE TABLE movimentacoes (
    id_movimento INT AUTO_INCREMENT PRIMARY KEY,
    descricao VARCHAR(100),
    valor DECIMAL(10,2) NOT NULL,
    data_movimento DATE,
    tipo ENUM('receita', 'despesa') NOT NULL, 
    categoria_id INT,
    usuario_id INT,
    CONSTRAINT fk_movimentacoes_categoria FOREIGN KEY (categoria_id) 
        REFERENCES categorias(id_categoria),
    CONSTRAINT fk_movimentacoes_usuario FOREIGN KEY (usuario_id) 
        REFERENCES usuarios(id_usuario)
);