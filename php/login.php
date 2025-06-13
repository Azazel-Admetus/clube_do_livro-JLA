<?php

require_once 'conn.php';
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $senha = trim($_POST['senha']);

    if(!empty($email) && !empty($senha) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $stmt = $conn->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($senha, $user['senha'])) {
            session_start();
            $_SESSION['username'] = $user['nome'];
            $_SESSION['email'] = $user['email'];
            header("Location:../html/home.html?processo=sucesso");
            exit();
        } else {
            header("Location:../html/login.html?error=credenciais_invalidas");
            exit();
        }
    } else {
        header("Location:../html/login.html?error=campos_vazios");
        exit();
    }
}