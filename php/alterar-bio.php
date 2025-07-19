<?php
require_once 'conn.php'; //arquivo de conexão do banco de dados
session_start(); //inicia a sessão
$user_id = $_SESSION['user_id']; //pega o id do usuário na sessão
if($_SERVER['REQUEST_METHOD'] == 'POST'){ //verifica o tipo de requisição
    $bio = trim(strip_tags($_POST['bio'])); //pega o valor do input e sanitiza
    if(!empty($bio)){ //verifica se a biografia enviada está vazia
        $stmt = $conn->prepare("UPDATE users SET biografia = :bio WHERE id = :id"); //insere a biografia no banco de dados
        $stmt->bindValue(':bio', $bio);
        $stmt->bindValue(':id', $user_id);
        if($stmt->execute()){
            header('Location:../html/loading.html?msg=Salvando...&redirect=../html/perfil.php?update=True');
            exit;
        } else{
            header('Location:../html/alterar_biografia.html?update=Error');
            exit;
        }
    } else {
        header('Location:../html/alterar_biografia.html?update=Empty');
        exit;
    }   
}
?>