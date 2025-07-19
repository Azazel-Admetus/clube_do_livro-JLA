<?php
require "cloud-conn.php"; //arquivo de conexão do cloudinary
require "conn.php"; //arquivo de conexão do banco de dados
session_start();
if($_SERVER['REQUEST_METHOD'] == "POST"){
    if(isset($_FILES['imagem_usuario'])){ //verifica se mandou imagem
        $verificar_tipo = ['image/jpeg', 'image/png']; // verifica o tipo de arquivo
        $maxsize = 2 * 1024 * 1024; //define o tamanho máximo do arquivo
        $tipo_arquivo = $_FILES['imagem_usuario']['type']; // pega o tipo do arquivo mandado
        $tamanho_arquivo = $_FILES['imagem_usuario']['size'];//pega o tamanho do arquivo mandado
        $tmp_arquivo = $_FILES['imagem_usuario']['tmp_name'];//caminho temporário do arquivo mandado
        $email = $_SESSION['email']; //pega o email do usuário armazenado na sessão
        //faz as verificações
        if(!in_array($tipo_arquivo, $verificar_tipo)){
            header('Location:../html/inserir_imagem_perfil.html?error=invalid_type');
            exit;
        }
        if($tamanho_arquivo > $maxsize){
            header('Location:../html/inserir_imagem_perfil.html?error=size');
            exit;
        }
        if(getimagesize($tmp_arquivo) === false){ 
            header('Location:../html/inserir_imagem_perfil.html?error=not_image');
            exit;
        }
        try{
            //faz o upload no cloudinary
            $resultado = $cloudinary->uploadApi()->upload($tmp_arquivo, [
                'folder' => 'imagem_perfil_users-narrify', 
                'public_id' => 'user_' . md5($email),
                'overwrite' => true
            ]);
            $url_imagem = $resultado['secure_url']; //pega a url da imagem uploadada
            $stmt = $conn->prepare("UPDATE users SET imagem_perfil_url = :url_imagem WHERE email = :email"); //insere a url da imagem uploadada no banco de dados
            $stmt->bindValue(':url_imagem', $url_imagem);
            $stmt->bindValue(':email', $email);
            if($stmt->execute()){
                header('Location:../html/loading.html?msg=Carregando...&redirect=../html/perfil.php?upload=success&url=' . urlencode($url_imagem));
                exit;
            }else{
                header('Location:../html/inserir_imagem_perfil.html?error=db_update_failed');
                exit;
            }
        } catch(Exception $e){
            header('Location:../html/inserir_imagem_perfil.html?error=upload_failed');
            exit;
        }
    }else{
        header('Location:../html/inserir_imagem_perfil.html?error=not_file');
        exit;
    }
}else{
    header('Location:../html/inserir_imagem_perfil.html?error=form');
    exit;
}
?>