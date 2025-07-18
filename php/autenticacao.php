<?php
// ini_set('display_errors', 1); para quando for debugar
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
require "conn.php";
require "../vendor/autoload.php";
require "enviarCodigo.php";
session_start();

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        header('Location:../html/autenticacao.html?error=invalid_email');
        exit;
    }
    //vou verificar se o email existe no banco de dados
    $stmtV = $conn->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
    $stmtV->bindValue(':email', $email);
    $stmtV->execute();
    $count = $stmtV->fetchColumn();
    if($count == 0){
        header('Location:../html/cadastro.html?error=email_!exists');
        exit;
    }

    $_SESSION['email_autenticacao'] = $email;
    $codigo = rand(100000, 999999);
    $stmt = $conn->prepare("UPDATE users SET codigo_verificacao = :codigo, verificado = 0 WHERE email = :email ");
    $stmt->bindValue(':codigo', $codigo);
    $stmt->bindValue(':email', $email);
    if($stmt->execute()){
        if(enviarCodigo($email, $codigo)){
            header("Location:../html/verificar_codigo.html");
            exit;
        }else{
            echo "Erro na funcao";
            exit;
        }
    }else{
        echo "erro no stmt execute";
        exit;
    }

}else{
    echo "form nao enviado";
    exit;
}
