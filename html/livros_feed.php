<?php
require "../php/conn.php";

$pesquisa = isset($_GET['q']) ? trim($_GET['q']) : null;

if($pesquisa){
    $stmt = $conn->prepare("
        SELECT * FROM livros_recomendados
        WHERE tags LIKE :pesquisa
        ORDER BY data_cadastro DESC
    ");

    $stmt->bindValue(":pesquisa", "%{$pesquisa}%");
}else{
    $stmt = $conn->prepare("
        SELECT *
        FROM livros_recomendados
        ORDER BY data_cadastro DESC
    ");
}

$stmt->execute();
$livros = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/livros-feed.css?v=1.1">
    <title>Explore as recomendações de livros</title>
</head>
<body>
    <header>
        <a href="home.php">
            <img src="../img/logo-secundária-removebg.png" alt="logo do clube do livro">
        </a>
    </header>
    <main>
        <h1>Explore as nossas recomendações literárias</h1>
        <form method="GET" action="">
            <input type="search" name="q" placeholder="Pesquisar pelas tags" value="<?= htmlspecialchars($pesquisa ?? '') ?>">
            <button type="submit"></button> 
        </form>
        <section class="card-container">
            <?php if (count($livros) > 0): ?>
                <?php foreach ($livros as $livro): ?>
                    <div class="card">
                        <img src="<?= htmlspecialchars($livro['capa_livro']) ?>" alt="Capa de <?= htmlspecialchars($livro['nome_livro']) ?>">
                        <h2><?= htmlspecialchars($livro['nome_livro']) ?></h2>
                        <h6>#<?= htmlspecialchars($livro['tags']) ?></h6>
                        <a href="<?= htmlspecialchars($livro['link_afiliado']) ?>" target="_blank">Acessar</a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="sem-resultados">Nenhum livro encontrado.</p>
            <?php endif; ?>
        </section>
    <a href="https://www.escolajoaquimdelima.com.br/">
        <img src="../img/Logo-JLA.jpg" alt="Logo da Escola Joaquim de Lima Avelino" class="logo-jla">
    </a>        
    </main>
</body>
</html>