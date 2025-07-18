<?php
require_once '../php/conn.php';
$erro = '';
$resenhas = [];
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
                <button type="submit">Buscar</button>
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
            <!-- Mais artigos de resenhas podem ser adicionados aqui -->
    </main>
    <footer>
        <img src="../img/Logo-JLA-sem-bg.png" alt="">
    </footer>
</body>
</html>