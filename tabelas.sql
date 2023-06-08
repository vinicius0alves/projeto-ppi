CREATE TABLE tbl_anunciante(
    codigo INT PRIMARY KEY auto_increment,
    nome VARCHAR(100),
    cpf VARCHAR(14),
    email VARCHAR(100),
    hash_senha VARCHAR(100),
    telefone VARCHAR(20)
)ENGINE=InnoDB;

CREATE TABLE tbl_categoria(
    codigo INT PRIMARY KEY auto_increment,
    nome VARCHAR(100),
    descricao VARCHAR(100)
)ENGINE=InnoDB;

CREATE TABLE tbl_anuncio (
  codigo INT PRIMARY KEY auto_increment,
  titulo VARCHAR(100),
  descricao VARCHAR(100),
  preco DECIMAL(10, 2),
  data_hora DATETIME,
  cep VARCHAR(8),
  bairro VARCHAR(100),
  cidade VARCHAR(100),
  estado VARCHAR(3),
  codCategoria INT,
  codAnunciante INT,
  FOREIGN KEY (codCategoria) REFERENCES tbl_categoria(codigo),
  FOREIGN KEY (codAnunciante) REFERENCES tbl_anunciante(codigo)
)ENGINE=InnoDB;

CREATE TABLE tbl_foto (
    codAnuncio INT,
    nomeArqFoto VARCHAR(100),
    FOREIGN KEY (codAnuncio) REFERENCES tbl_anuncio(codigo)
)ENGINE=InnoDB;

CREATE TABLE tbl_interesse (
    codigo INT PRIMARY KEY auto_increment,
    mensagem VARCHAR(100),
    data_hora DATETIME,
    contato VARCHAR(20),
    codAnuncio INT,
    FOREIGN KEY (codAnuncio) REFERENCES tbl_anuncio(codigo)
)ENGINE=InnoDB;

CREATE TABLE tbl_base_enderecos_ajax (
    cep VARCHAR(8),
    bairro VARCHAR(100),
    cidade VARCHAR(100),
    estado VARCHAR(3)
)ENGINE=InnoDB;
