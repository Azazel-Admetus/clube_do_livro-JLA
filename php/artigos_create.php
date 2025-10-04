<?php
require_once 'conn.php';
require "error_log.php";
session_start();

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $titulo = trim(strip_tags($_POST['titulo']));
    $descricao = trim(strip_tags($_POST['descricao']));
    $conteudo = trim(strip_tags($_POST['conteudo']));
    $tags = explode(",", $_POST['tags']);
    $tags = array_map('trim', $tags);

    // Pegando o ID do usuário pela sessão
    $id_autor = $_SESSION['user_id'] ?? null;

    if ($id_autor && !empty($titulo) && !empty($descricao) && !empty($conteudo)) {
        try {
            // Inserir artigo
            $stmtArt = $conn->prepare("INSERT INTO artigos (usuario_id, titulo, conteudo, descricao) VALUES (:user_id, :titulo, :conteudo, :descricao)");
            $stmtArt->bindParam(":user_id", $id_autor);
            $stmtArt->bindParam(":titulo", $titulo);
            $stmtArt->bindParam(":conteudo", $conteudo);
            $stmtArt->bindParam(":descricao", $descricao);

            if ($stmtArt->execute()) {
                $artigoId = $conn->lastInsertId(); // ID do artigo recém-criado

                // Preparar statements para reaproveitar
                $stmtTagSelect = $conn->prepare("SELECT id FROM tags WHERE nome = :tag");
                $stmtTagInsert = $conn->prepare("INSERT INTO tags (nome) VALUES (:tag)");
                $stmtArtTag = $conn->prepare("INSERT INTO artigo_tags (artigo_id, tag_id) VALUES (:artigo_id, :tag_id)");

                foreach ($tags as $tag) {
                    if (empty($tag)) continue;

                    // Verifica se a tag já existe
                    $stmtTagSelect->execute([":tag" => $tag]);
                    $tagRow = $stmtTagSelect->fetch(PDO::FETCH_ASSOC);

                    if ($tagRow) {
                        $tagId = $tagRow['id'];
                    } else {
                        // Insere nova tag
                        $stmtTagInsert->execute([":tag" => $tag]);
                        $tagId = $conn->lastInsertId();
                    }

                    // Liga artigo com a tag
                    $stmtArtTag->execute([
                        ":artigo_id" => $artigoId,
                        ":tag_id" => $tagId
                    ]);
                }
                header('Location:../html/artigos_create.html?insert=true');
                exit();
            } else {
                header('Location: ../html/artigos_create.html?error=error');
                exit();
            }
        } catch (PDOException $e) {
            header('Location:../html/artigos_create.html?error=insertArtigo');
            exit();
        }
    } else {
        header('Location:../html/artigos_create.html?error=empty');
        exit();
    }
}
?>
