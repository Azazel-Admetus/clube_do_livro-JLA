<?php
require_once 'conn.php';
session_start();

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if($_POST['nova_senha'] == $_POST['confirmar_senha']){
        if(strlen($_POST['nova_senha']) < 8){
            header('Location:../html/alterar_senha.html?error=senha_curta');
            exit;
        }
        $senha = password_hash($_POST['nova_senha'], PASSWORD_DEFAULT);
        $email = $_SESSION['email'];
        $stmt = $conn->prepare('UPDATE users SET senha = :senha WHERE email = :email');
        $stmt->bindValue(':senha', $senha);
        $stmt->bindValue(':email', $email);
        if($stmt->execute()){
            header('Location:../html/perfil.html?success=senha_alterada');
            exit;
        } else {
            header('Location:../html/alterar_senha.html?error=alterar_senha');
            exit;
        }
    }else{
        header('Location:../html/alterar_senha.html?error=senhas_diferentes');
        exit;
    }
}else{
    header('Location:../html/alterar_senha.html?error=requisicao_invalida');
    exit;
}



?>
