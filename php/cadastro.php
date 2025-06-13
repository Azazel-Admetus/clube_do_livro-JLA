<?php 

require_once 'conn.php';
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

    if(!empty($nome) && !empty($email) && !empty($senha)){
        $stmt = $conn->prepare('SELECT COUNT(*) FROM users WHERE nome = :nome OR email = :email');
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $exists = $stmt->fetchColumn();
        if ($exists > 0){
            header("Location:../html/cadastro.html?error=credencias_existentes");
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