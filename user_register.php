<?php

require "conexaoMysql.php";
$pdo = mysqlConnect();

function isDuplicateCpf($pdo, $cpf)
{
  $sql = "SELECT COUNT(*) AS count FROM tbl_anunciante WHERE cpf = :cpf";

  $stmt = $pdo->prepare($sql);
  $stmt->bindParam(":cpf", $cpf);
  $stmt->execute();

  $result = $stmt->fetch(PDO::FETCH_ASSOC);

  return $result['count'] > 0;
}

function isDuplicateEmail($pdo, $email)
{
  $sql = "SELECT COUNT(*) AS count FROM tbl_anunciante WHERE email = :email";

  $stmt = $pdo->prepare($sql);
  $stmt->bindParam(":email", $email);
  $stmt->execute();

  $result = $stmt->fetch(PDO::FETCH_ASSOC);

  return $result['count'] > 0;
}

class RequestResponse
{
  public $success;
  public $errors;

  function __construct($success, $errors)
  {
    $this->success = $success;
    $this->errors = $errors;
  }
}

$nome = $_POST["name"] ?? "";
$cpf = $_POST["cpf"] ?? "";
$email = $_POST["email"] ?? "";
$telefone = $_POST["phone"] ?? "";
$password = $_POST["password"] ?? "";
$password_confirm = $_POST["passwordConfirm"] ?? "";

$hashsenha = password_hash($password, PASSWORD_DEFAULT);
$hashsenha2 = password_hash($password_confirm, PASSWORD_DEFAULT);

try {

  if ($password != $password_confirm) { // Verificar se as senhas sao identicas
    $response = new RequestResponse(false, "Senhas diferentes.");
  } else if (isDuplicateCpf($pdo, $cpf)) { // Verificar se já existe um cadastro com o mesmo CPF
    $response = new RequestResponse(false, "CPF ja cadastrado.");
  } else if (isDuplicateEmail($pdo, $email)) { // Verificar se já existe um cadastro com o mesmo email
    $response = new RequestResponse(false, "Email ja cadastrado.");
  } else {
    // Inicia o cadastro do usuario
    $pdo->beginTransaction();

    $sql = "INSERT INTO tbl_anunciante (nome, cpf, email, hash_senha, telefone) VALUES (:nome, :cpf, :email, :hashsenha, :telefone)";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":nome", $nome);
    $stmt->bindParam(":cpf", $cpf);
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":hashsenha", $hashsenha);
    $stmt->bindParam(":telefone", $telefone);
    $stmt->execute();

    $pdo->commit();

    $response = new RequestResponse(true, []);
  }

} catch (PDOException $e) {
  $pdo->rollBack();
  error_log($e->getMessage(), 3, 'log.php');
  $response = new RequestResponse(false, $e->getMessage());
}


header('Content-Type: application/json; charset=utf-8');
echo json_encode($response);