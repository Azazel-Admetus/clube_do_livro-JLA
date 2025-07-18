<?php
require_once 'conn.php';// arquivo de conexão
session_start();//inicia a sessão
if($_SERVER['REQUEST_METHOD'] == 'POST'){ //verifica o tipo de requisição
    if($_POST['nova_senha'] == $_POST['confirmar_senha']){ //verifica se ambos os campos sao iguais
        if(strlen($_POST['nova_senha']) < 8){ //verifica o tamanho da senha digitada
            header('Location:../html/alterar_senha.html?error=senha_curta');
            exit;
        }
        $senha = password_hash($_POST['nova_senha'], PASSWORD_DEFAULT); //criptografa a senha
        $email = $_SESSION['email']; //pega o email da sessão
        $stmt = $conn->prepare('UPDATE users SET senha = :senha WHERE email = :email'); //altera no banco de dados
        $stmt->bindValue(':senha', $senha);
        $stmt->bindValue(':email', $email);
        if($stmt->execute()){
            header('Location:../html/loading.html?msg=Alterando...&redirect=../html/perfil.php?success=senha_alterada');
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
