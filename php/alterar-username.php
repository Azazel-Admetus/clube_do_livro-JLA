<?php
require_once "conn.php";
session_start();
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $nome = trim(strip_tags($_POST['nome']));
    $email = $_SESSION['email'];
    if(!empty($nome)){
        $stmt = $conn->prepare("UPDATE users SET nome = :nome WHERE email = :email");
        $stmt->bindValue(':nome', $nome);
        $stmt->bindValue(':email', $email);
        if($stmt->execute()){
            header('Location:../html/alterar-username.html?insert=True');
            exit;
        } else{
            header('Location:../html/alterar-username.html?insert=Error');
            exit;
        }
    }
}