<?php
require_once "conn.php";//arquivo de conexão do banco de dados
session_start(); //inicia a sessão
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $nome = trim(strip_tags($_POST['nome'])); //pega o valor do formulário e sanitiza
    $email = $_SESSION['email'];
    if(!empty($nome)){ //verifica se está vazio o campo
        $stmt = $conn->prepare("UPDATE users SET nome = :nome WHERE email = :email"); //altera o valor do nome de usuário
        $stmt->bindValue(':nome', $nome);
        $stmt->bindValue(':email', $email);
        if($stmt->execute()){
            header('Location:../html/loading.html?msg=Alterando...&redirect=../html/perfil.php?insert=True');
            exit;
        } else{
            header('Location:../html/alterar-username.html?insert=Error');
            exit;
        }
    }
}
?>