<?php
require "conn.php";
require "error_log.php"; //arquivo de conexão do banco de dados
require "../vendor/autoload.php"; //caminho do vendor para utilizar a biblioteca PHPMailer
require "enviarCodigo.php"; //arquivo onde está a função que utiliza essa biblioteca
session_start(); //inicia a sessão
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL); //pega o email e sanitiza
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){ //verifica se é válido o endereço de email
        header('Location:../html/autenticacao.html?error=invalid_email');
        exit;
    }
    //vou verificar se o email existe no banco de dados
    $stmtV = $conn->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
    $stmtV->bindValue(':email', $email);
    $stmtV->execute();
    $count = $stmtV->fetchColumn(); 
    if($count == 0){ //verifica se tem esse email no banco de dados
        header('Location:../html/cadastro.html?error=email_!exists');
        exit;
    }
    $_SESSION['email_autenticacao'] = $email; //pega o email armazenado na sessão
    $codigo = rand(100000, 999999); //gera um código de 6 dígitos
    $stmt = $conn->prepare("UPDATE users SET codigo_verificacao = :codigo, verificado = 0 WHERE email = :email "); //insere no banco de dados esse código para verificação posterior
    $stmt->bindValue(':codigo', $codigo);
    $stmt->bindValue(':email', $email);
    if($stmt->execute()){
        if(enviarCodigo($email, $codigo)){ //usa a função para enviar código
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
?>