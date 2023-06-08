<?php

require "conexaoMysql.php";
$pdo = mysqlConnect();


$offset = $_GET["offset"] ?? "";
$anuncios = [];

class ProductAnunc
{
    public $description;
    public $titulo;
    public $price;
    public $imagePath;

    function __construct($description, $titulo, $price, $imagePath)
    {
        $this->description = $description;
        $this->titulo = $titulo;
        $this->price = $price;
        $this->imagePath = $imagePath;
    }
}

try {
    $sql = <<<SQL
    SELECT A.descricao, A.preco, A.titulo, F.nomeArqFoto
    FROM tbl_anuncio as A
    INNER JOIN tbl_foto as F
    ON A.codigo > F.codAnuncio
    LIMIT 6 OFFSET ?
    SQL;

    $stmt = $pdo->prepare($sql);

    $stmt->execute([$offset]);

    while ($row = $stmt->fetch()) {
        $titulo = htmlspecialchars($row['titulo']);
        $salario = htmlspecialchars($row['preco']);
        $descricao = htmlspecialchars($row['descricao']);
        $nomeArqFoto = htmlspecialchars($row['nomeArqFoto']);

        $anuncios[] = new ProductAnunc($descricao, $titulo, $salario, $nomeArqFoto);
    }

} catch (Exception $e) {
    exit('Ocorreu uma falha: ' . $e->getMessage());
}


header('Content-Type: application/json; charset=utf-8');
echo json_encode($anuncios);
