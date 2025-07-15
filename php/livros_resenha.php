<?php
ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once 'cloud-conn.php';
require_once 'conn.php';
session_start();
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    //buscando os dados do formulário
    $titulo = trim(strip_tags($_POST['titulo']));
    $autor_livro = trim(strip_tags($_POST['autor']));
    $genero = trim(strip_tags($_POST['genero']));
    $resenha = trim(strip_tags($_POST['resenha']));
    $sinopse = trim(strip_tags($_POST['sinopse']));
    //pegando o id e email do usário na sessão
    $username = $_SESSION['username'];
    $email = $_SESSION['email'];
    //pegando informações para fazer a verificação da imagem de capa do livro
    $verificar_tipo = ['image/jpeg', 'image/png'];
    $maxsize = 2 * 1024 * 1024;
    $tipo_arquivo = $_FILES['capa']['type'];
    $tamanho_arquivo = $_FILES['capa']['size'];
    $tmp_arquivo = $_FILES['capa']['tmp_name'];
    //fazendo as verificações
    if(!in_array($tipo_arquivo, $verificar_tipo)){
            header('Location:../html/livros_resenha.html?error=invalid_type');
            exit;
        }
        if($tamanho_arquivo > $maxsize){
            header('Location:../html/livros_resenha.html?error=size');
            exit;
        }
        if(getimagesize($tmp_arquivo) === false){ 
            header('Location:../html/livros_resenha.html?error=not_image');
            exit;
        }
        //bora pôr a mão na massa
        try{
            $resultado = $cloudinary->uploadApi()->upload($tmp_arquivo, [
                'folder' => 'imagem_capa_livros-narrify', 
                'public_id' => 'livro' . md5($email),
                'overwrite' => true
            ]);
            $url_imagem = $resultado['secure_url'];
            $stmt = $conn->prepare("INSERT INTO Livros_resenha (titulo, sinopse, genero, resenha, url_imagem, autor, autor_livro) VALUES (:titulo, :genero, :resenha, :url_imagem, :autor, :autor_livro)");
            $stmt->bindValue(':titulo', $titulo);
            $stmt->bindValue(':sinopse', $sinopse);
            $stmt->bindValue(':genero', $genero);
            $stmt->bindValue(':resenha', $resenha);
            $stmt->bindValue(':url_imagem', $url_imagem);
            $stmt->bindValue(':autor', $username);
            $stmt->bindValue(':autor_livro', $autor_livro);
            if($stmt->execute()){
                header('Location:../html/livros_resenha.html?insert=success');
                exit;
            }else{
                header('Location:../html/livros_resenha.html?error=db_update_failed');
                exit;
            }
        } catch(Exception $e){
            echo "Erro ao fazer o upload da imagem: " . $e->getMessage();
            //header('Location:../html/livros_resenha.html?error=upload_failed');
            //exit;
        }
}
?>
