<?php 

require_once 'conn.php';
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = trim(strip_tags($_POST['nome']));
    $nome = mb_substr($nome, 0, 100);
    $email = trim(strip_tags($_POST['email']));
    $email = mb_substr($email, 0, 100);
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

    if(!empty($nome) && !empty($email) && !empty($senha) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $stmt = $conn->prepare('SELECT COUNT(*) FROM users WHERE nome = :nome OR email = :email');
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $exists = $stmt->fetchColumn();
        if ($exists > 0){
            header("Location:../html/login.html?error=user_exists");
            exit();
        } else {
            $stmt = $conn->prepare("INSERT INTO users (nome, email, senha) VALUES (:nome, :email, :senha)");
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':senha', $senha);
            if ($stmt->execute()){
                session_start();
                $_SESSION['username'] = $nome;
                $_SESSION['email'] = $email;
                header("Location:../html/home.html?processo=sucesso");
                exit();
            } else{
                header('Location:../html/cadastro.html?error=erro_cadastro');
                exit();
            }
        }
    } else{
        header('Location:../html/cadastro.html?error=campos_vazios');
        exit();
    }
}