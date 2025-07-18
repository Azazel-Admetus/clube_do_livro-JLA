<?php
require "conn.php";
session_start();
$codigo = trim($_POST['codigo']);
$email = $_SESSION['email_autenticacao'];
$fluxo = $_SESSION['fluxo_autenticacao'];
if($fluxo == 'perfil' || $fluxo == 'esqueci_senha'){
    header('Location:../html/loading.html?msg=Redirecionando...&redirect=../html/alterar_senha.html');
    exit;
}


$stmt = $conn->prepare("SELECT codigo_verificacao FROM users WHERE email = :email");
$stmt->bindValue(":email", $email);
if($stmt->execute()){
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if(hash_equals($user['codigo_verificacao'], $codigo)){
        $stmt2 = $conn->prepare("UPDATE users SET verificado = 1 WHERE email = :email");
        $stmt2->bindValue(":email", $email);
        if($stmt2->execute()){
            header('Location:../html/loading.html?msg=Verificando...&redirect=../html/home.php?processo=verificacao_true');
            exit;
        }else{
            echo "Código incorreto. Tente novamente";
        }
    }else{
        header('Location:../html/verificar_codigo.html?error=codigo_incorreto');
        exit;
    }
}
