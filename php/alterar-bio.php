<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once 'conn.php';
session_start();
$user_id = $_SESSION['user_id'];
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $bio = trim(strip_tags($_POST['bio']));
    if(!empty($bio)){
        $stmt = $conn->prepare("UPDATE users SET biografia = :bio WHERE id = :id");
        $stmt->bindValue(':bio', $bio);
        $stmt->bindValue(':id', $user_id);
        if($stmt->execute()){
            header('Location:../html/perfil.html?update=True');
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