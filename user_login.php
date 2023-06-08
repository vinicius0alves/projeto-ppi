<?php

function checkLogin($pdo, $email, $senha)
{
  $sql = <<<SQL
    SELECT hash_senha
    FROM tbl_anunciante
    WHERE email = ?
    SQL;

  try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);
    $row = $stmt->fetch();
    if (!$row)
      return false; // nenhum resultado (email não encontrado)

    return password_verify($senha, $row['hash_senha']);
  } catch (Exception $e) {
    exit('Falha inesperada: ' . $e->getMessage());
  }
}

class RequestResponse
{
  public $success;
  public $detail;

  function __construct($success, $detail)
  {
    $this->success = $success;
    $this->detail = $detail;
  }
}

require "conexaoMysql.php";
$pdo = mysqlConnect();

$email = $_POST["email"] ?? "";
$senha = $_POST["senha"] ?? "";

if (checkLogin($pdo, $email, $senha))
  $response = new RequestResponse(true, 'home.html');
else
  $response = new RequestResponse(false, '');

header('Content-Type: application/json; charset=utf-8');
echo json_encode($response);