<?php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['user_id'] == '') {
    header('Location:login.html');
    exit();
}
require_once '../php/conn.php';
$username = $_SESSION['username'];
$stmt = $conn>prepare("SELECT * FROM Livros_resenha WHERE autor = :username");
$stmt->bindValue(':username', $username);
if($stmt->execute()){
    $resenhas = $stmt->fetch(PDO::FETCH_ASSOC);
    if(!$resenhas) {
        die("Nenhuma resenha encontrada para o usuário: $username");
        exit();
    }
    //agora vou pegar as informações do usuario
    $stmt2 = $conn->prepare('SELECT biografia, imagem_perfil_url FROM users WHERE nome= :nome');
    $stmt2->bindValue(':nome', $username);
    if($stmt2->execute()){
        $user_info = $stmt2->fetch(PDO::FETCH_ASSOC);
        if(!$user_info) {
            die("Erro ao buscar informações do usuário.");              
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/resenha.css">
    <title>Resenhas literárias</title>
</head>
<body>
    <header>
        <a href="home.html">
            <img src="../img/logo-principal-slogan-transparente.png" alt="Logo do Clube do Livro" class="logo">
        </a>
    </header>
    <main>
        <header>
        <div class="info_livro">
            <h1 class="titulo">Título: <?= htmlspecialchars($resenhas['titulo']); ?></h1>
            <h5 class="genero">Gênero: <?= htmlspecialchars($resenhas['genero']); ?></h5>
            <h5 class="autor">Autor: <?= htmlspecialchars($resenhas['autor_livro']); ?></h5>
            <h5 class="data">Data de publicação: <?= htmlspecialchars($resenhas['data']); ?></h5>
        </div>
        <div class="livro_capa">
            <img src="<?= htmlspecialchars($resenhas['url_imagem']);?>"> <!--Aqui ficará a imagem da capa do livro-->
        </div>
        </header>
        <section class="resenha">
            <p class="texto_resenha">
                <?= nl2br(htmlspecialchars($resenhas['resenha'])); ?>
            </p>
        </section>
        <footer>
            <div class="info_usuario">
                <img src="<?= htmlspecialchars($user_info['imagem_perfil_url']);?>" alt="Foto do usuário" class="foto_usuario">
                <h5 class="nome_usuario">Autor da Resenha: <?= htmlspecialchars($username);?></h5>
                <p class="bio">Bio do usuário: <?= htmlspecialchars($user_info['biografia']);?></p>
            </div>
        </footer>
    </main>
</body>
</html>