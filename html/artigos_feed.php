<?php
require "../php/conn.php";
$status = "concluido";
$stmt = $conn->prepare("SELECT id, titulo, descricao FROM artigos WHERE status = :status ORDER BY criado_em DESC ");
$stmt->bindParam(":status", $status);
$stmt->execute();
$artigos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$feedHTML = "";

foreach ($artigos as $artigo){
    $stmtTAGS = $conn->prepare("SELECT t.nome FROM tags t INNER JOIN artigo_tags at ON t.id = at.tag_id WHERE at.artigo_id = :artigo_id");
    $stmtTAGS->bindParam(':artigo_id', $artigo['id']);
    $stmtTAGS->execute();
    $tags = $stmtTAGS->fetchAll(PDO::FETCH_ASSOC);

    $tagsHTML = "";

    foreach($tags as $tag){
        $tagsHTML .= "<span class='tag'>#" . htmlspecialchars($tag['nome']) . "</span>";
    }

    $feedHTML .= "
        <a href='artigos.php?id=" . htmlspecialchars($artigo['id']) . "'>
            <section>
                <h2>" . htmlspecialchars($artigo['titulo']) . "</h2>
                <p>" . htmlspecialchars($artigo['descricao']) . "</p>
                <div class='tags'>$tagsHTML</div>
            </section>
        </a>";

}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/artigos_feed.css">
    <title>Explore artigos</title>
</head>
<body>
    <header>
        <a href="home.php">
            <img src="../img/logo-secundária-removebg.png" alt="Logo do clube do livro narrify">
        </a>
    </header>
   <h1>Explore artigos criados pela comunidade</h1> 
    <?= $feedHTML ?>
    <a href="https://www.escolajoaquimdelima.com.br/">
        <img src="../img/Logo-JLA.jpg" alt="Logo da Escola Joaquim de Lima Avelino" class="logo-jla">
    </a>
</body>
</html>