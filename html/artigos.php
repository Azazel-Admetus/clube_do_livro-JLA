<?php
require "../php/conn.php";

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Artigo inválido.");
}

$id_artigo = (int) $_GET['id'];

$stmt = $conn->prepare("SELECT titulo, conteudo FROM artigos WHERE id = :id");
$stmt->bindParam(":id", $id_artigo, PDO::PARAM_INT);
$stmt->execute();

$content = $stmt->fetch(PDO::FETCH_ASSOC);

if ($content) {
    $titulo = $content['titulo'];
    $conteudo = $content['conteudo'];
} else {
    $titulo = "Artigo não encontrado";
    $conteudo = "";
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Leia o artigo">
    <meta name="keywords" content="artigos, ler artigos">
    <meta name="author" content="Site criado por: Azazel Admetus">
    <meta name="robots" content="index, follow">
    <meta name="language" content="pt-BR">
    <meta name="format-detection" content="telephone=no">
    <link rel="icon" href="../img/logo_favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="../css/artigos.css">
    <title><?= htmlspecialchars($titulo); ?></title>
</head>
<body>
    <header>
        <img src="../img/logo-secundária-removebg.png" alt="Logo do site Narrify - Versos e Prosa">
        <a href="home.php" class="link">Início</a>
    </header>
    <main>
        <h1><?= htmlspecialchars($titulo); ?></h1>
        <article>
            <!-- aqui vem a descricao -->
            <div>
                <p><?= nl2br($conteudo);?></p>
            </div>
        </article>
    </main>
    <a href="https://www.escolajoaquimdelima.com.br/">
        <img src="../img/Logo-JLA.jpg" alt="Logo da Escola Joaquim de Lima Avelino" class="logo-jla">
    </a>
</body>
</html>