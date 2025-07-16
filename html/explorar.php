<?php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['user_id'] == '') {
    header('Location:login.html');
    exit();
}
require_once '../php/conn.php';
$username = $_SESSION['username'];
$stmt = $conn->prepare("SELECT id, titulo, sinopse FROM Livros_resenha WHERE autor = :username");
$stmt->bindValue(':username', $username);
if($stmt->execute()){
    $resenhas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $erro = '';
    if(!$resenhas) {
        $erro = "Nenhuma resenha encontrada. Tente novamente mais tarde.";

    }

}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/explorar.css">
    <title>Explore as resenhas criadas</title>
</head>
<body>
    <header>
        <a href="home.html">
            <img src="../img/logo-principal-slogan-transparente.png" alt="Logo do Clube do Livro" class="logo">
        </a>
        <div class="busca">
            <label for="search">Filtrar</label>
            <input type="search" id="search" name="q" placeholder="Buscar por título de livro...">
            <button type="submit">Buscar</button>
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