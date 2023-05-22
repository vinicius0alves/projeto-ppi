<?php
    require "conexaoMysql.php";
    $pdo = mysqlConnect();

    $nome = $_POST["name"] ?? "";
    $cpf = $_POST["cpf"] ?? "";
    $email = $_POST["email"] ?? "";
    $telefone = $_POST["phone"] ?? "";
    $password = $_POST["password"] ?? "";

    $hashsenha = password_hash($password, PASSWORD_DEFAULT);

    try {
        $sql = <<<SQL

        INSERT INTO tbl_anunciante (nome, cpf, email, hash_senha, telefone)
        VALUES (?, ?, ?, ?, ?)
        SQL;
      
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
          $nome, $cpf, $email, $hashsenha, $telefone
        ]);
      
        header("location: login.html");
        exit();
      } 
      catch (Exception $e) {  
        error_log($e->getMessage(), 3, 'log.php');
        exit('Falha ao cadastrar os dados: ' . $e->getMessage());
      }