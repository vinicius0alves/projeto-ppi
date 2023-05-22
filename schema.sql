CREATE TABLE tbl_anunciante(
    codigo INT PRIMARY KEY auto_increment,
    nome VARCHAR(100),
    cpf VARCHAR(14),
    email VARCHAR(100),
    hash_senha VARCHAR(100),
    telefone VARCHAR(20)
)ENGINE=InnoDB;