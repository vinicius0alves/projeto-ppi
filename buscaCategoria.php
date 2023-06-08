<?php
require "conexaoMysql.php";
$pdo = mysqlConnect();

class RequestResponse
{
  public $success;
  public $categorias;

  function __construct($success, $categorias)
  {
    $this->success = $success;
    $this->categorias = $categorias;
  }
}

$sql = <<<SQL
        SELECT * FROM tbl_categoria;
    SQL;

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $response = new RequestResponse(true, $categorias);

} catch (PDOException $e) {
    $response = new RequestResponse(true, $categorias);
    error_log($e->getMessage(), 3, 'log.php');
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode($response);