<?php
require "conn.php";
require "cloud-conn.php";

if ($_SERVER['REQUEST_METHOD'] === "POST"){
    // primeiro vamos pegar os dados normais, depois pegamos os dados da imagem
    $nome = trim(strip_tags($_POST['nome_livro']));
    $tags = explode(",", $_POST['tags']);     
    $tags = array_map('trim', $tags);         
    $tags_str = implode(', ', $tags);         

    $link = trim(strip_tags($_POST['link']));

    //vamos para os dados da imagem
    $verificar_tipo = ['image/jpeg', 'image/png'];
    $maxsize = 2 * 1024 * 1024;
    $tipo_arquivo = $_FILES['capa_livro']['type'];
    $tamanho_arquivo = $_FILES['capa_livro']['size'];
    $tmp_arquivo = $_FILES['capa_livro']['tmp_name'];
    if(!in_array($tipo_arquivo, $verificar_tipo)){
            header('Location:../html/cadastroLivros.php?error=invalid_type');
            exit;
        }
        if($tamanho_arquivo > $maxsize){
            header('Location:../html/cadastroLivros.php?error=size');
            exit;
        }
        if(getimagesize($tmp_arquivo) === false){ 
            header('Location:../html/cadastroLivros.php?error=not_image');
            exit;
        }
        //bora pôr a mão na massa
        try{
            $resultado = $cloudinary->uploadApi()->upload($tmp_arquivo, [
                'folder' => 'livros-recomendados-narrify', 
                'public_id' => 'livro_' . md5($nome . uniqid()),
                'overwrite' => true
            ]);
            $url_imagem = $resultado['secure_url'];
            $stmt = $conn->prepare("INSERT INTO livros_recomendados (nome_livro, capa_livro, tags, link_afiliado) VALUES (:nomeLivro, :capaLivro, :tags, :link)");
            $stmt->bindValue(':nomeLivro', $nome);
            $stmt->bindValue(':capaLivro', $url_imagem);
            $stmt->bindValue(':tags', $tags_str);
            $stmt->bindValue(':link', $link);
            if($stmt->execute()){
                header('Location:../html/cadastroLivros.php?error=success');
                exit;
            }else{
                header('Location:../html/cadastroLivros.php?error=db_update_failed');
                exit;
            }
        } catch(Exception $e){
            header('Location:../html/cadastroLivros.php?error=upload_failed');
            exit;
        }
}
?>