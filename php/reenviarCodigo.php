<?php
require_once 'conn.php';
require_once 'enviarCodigo.php';
session_start();
if(isset($_SESSION['email_autenticacao'])){
    $email = $_SESSION['email_autenticacao'];
    $codigo = rand(100000, 999999); // cria um código de 6 dígitos
    $stmt = $conn->prepare('UPDATE users SET codigo_verificacao = :codigo, verificado = 0 WHERE email= :email'); // insere o código no banco de dados
    $stmt->bindValue(':codigo', $codigo);
    $stmt->bindValue(':email', $email);
    if($stmt->execute()){
        if(enviarCodigo($email, $codigo)){
            header('../html/verificar_codigo.html?msg=codigo+reenviado');
            exit;
        }else{
            header('../html/verificar_codigo.html?msg=error+reenviar+codigo');
            exit;
        }
    }else{
        header('../html/verificar_codigo.html?msg=error+atualizar+codigo');
        exit;
    }
}else{
    echo "Sessão expirada ou email não econtrado. Tente novamente.";
}
?>