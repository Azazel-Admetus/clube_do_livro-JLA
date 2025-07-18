<?php
require_once '../php/conn.php'; // traz o arquivo de configuração de conexão
$erro = ''; //variável para tratamento de erro
$resenhas = []; //cria o array para colocar as resenhas
if(isset($_GET['autor']) && !empty(trim($_GET['autor']))){
    $autor = '%' . trim($_GET['autor']) . '%';
    $stmt = $conn->prepare("SELECT id, titulo, autor_livro, sinopse FROM Livros_resenha WHERE autor_livro LIKE :autor");
    $stmt->bindParam(':autor', $autor, PDO::PARAM_STR);
}else{
    $stmt = $conn->prepare("SELECT id, titulo, autor_livro, sinopse FROM Livros_resenha");
}
if($stmt->execute()){
    $resenhas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if(!$resenhas){
        $erro = "Nenhuma resenha encontrada.";
    }
}else{
    $erro = "Erro ao buscar resenhas";
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Explore as resenhas feitas pelos membros do clube literário Narrify">
    <meta name="keywords" content="Explorar, Explore, Resennhas, explorar resenhas">
    <meta name="author" content="Site criado por: Azazel Admetus e Kenzo_Susuna">
    <meta name="robots" content="index, follow">
    <meta name="language" content="pt-BR">
    <meta name="format-detection" content="telephone=no">
    <link rel="stylesheet" href="../css/explorar.css?v=1.2">
    <title>Explore as resenhas criadas</title>
</head>
<body>
    <header>
        <a href="home.php">
            <img src="../img/logo-principal-slogan-transparente.png" alt="Logo do Clube do Livro" class="logo">
        </a>
        <div class="busca">
            <form method="get">
                <label for="search">Filtrar</label>
                <input type="search" id="search" name="autor" placeholder="Buscar por autor...">
                <button type="submit" aria-label="Filtrar resenhas">Buscar</button>
            </form>
        </div>
    </header>
    <main>
        <section class="resenhas_wrapper">
            <h1>Explore as resenhas criadas</h1>
            <?php if ($erro): ?>
                <div class="mensagem-erro"><?= htmlspecialchars($erro); ?></div>
            <?php endif; ?>
            <div class="card_resenhas">
                <?php foreach($resenhas as $resenha): ?>
                    <article class="resenha">
                        <h2 class="titulo"><?= htmlspecialchars($resenha['titulo']);?></h2>
                        <h6 class="titulo">Autor: <?= htmlspecialchars($resenha['autor_livro']) ?></h6>
                        <p class="descricao"><?= htmlspecialchars($resenha['sinopse']);?></p>
                        <a href="resenhas.php?id=<?= htmlspecialchars($resenha['id']);?>" class="link_resenha">Leia mais</a>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>
    </main>
    <footer>
        <a href="https://www.escolajoaquimdelima.com.br/">
            <img src="../img/Logo-JLA-sem-bg.png" alt="Logo da Escola Joaquim de Lima Avelino">
        </a>
    </footer>
</body>
</html>