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