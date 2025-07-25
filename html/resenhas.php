<?php
session_start(); //inicia a sessão
if(!isset($_SESSION['user_id']) || $_SESSION['user_id'] == '') { //verifica se o usuário está logado
    header('Location:login.html');
    exit();
}
$id_resenha = $_GET['id'] ?? null; //pega o id da resenha que está como parâmetro na url
$erro = '';
if(!$id_resenha) { //verifica se tem id válido
    $erro = "ID da resenha não fornecido.";
    exit();
}
require_once '../php/conn.php'; //arquivo de conexão do banco de dados
$stmt = $conn->prepare("SELECT * FROM Livros_resenha WHERE id = :id"); // busca as resenhas com base no id da resenha
$stmt->bindValue(':id', $id_resenha);
if($stmt->execute()){
    $resenhas = $stmt->fetch(PDO::FETCH_ASSOC); //armazena as resenhas
    if(!$resenhas) {
        $erro = "Nenhuma resenha encontrada. Tente novamente mais tarde.";
        exit();
    }
    $usuario = $resenhas['autor'];
    //agora vou pegar as informações do autor da resenha
    $stmt2 = $conn->prepare('SELECT biografia, imagem_perfil_url FROM users WHERE nome= :nome'); //busca as informações do criador da resenha
    $stmt2->bindValue(':nome', $usuario);
    if($stmt2->execute()){
        $user_info = $stmt2->fetch(PDO::FETCH_ASSOC);
        if(!$user_info) {
            $erro = "Erro ao buscar informações do usuário.";              
            exit();
        }
    }else{
        $erro = "Erro ao buscar as informações do criador da resenha no banco de dados.";
    }
}else{
    $erro = "Erro ao buscar as resenhas no banco de dados";
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Página de Resenhas feitas pelos membros do clube do livro Narrify">
    <meta name="keywords" content="Resenhas, Resenhas feitas">
    <meta name="author" content="Site criado por: Azazel Admetus e Kenzo_Susuna">
    <meta name="robots" content="index, follow">
    <meta name="language" content="pt-BR">
    <meta name="format-detection" content="telephone=no">
    <link rel="icon" href="../img/logo_favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="../css/resenha.css?v=1.0">
    <title>Resenhas literárias</title>
</head>
<body>
    <header>
        <a href="home.php">
            <img src="../img/logo-principal-slogan-transparente.png" alt="Logo do Clube do Livro" class="logo">
        </a>
    </header>
    <main>
        <?php if ($erro): ?>
            <div class="mensagem-erro"><?= htmlspecialchars($erro); ?></div>
        <?php endif; ?>
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
                <img src="<?= htmlspecialchars(!empty($user_info['imagem_perfil_url']) ? $user_info['imagem_perfil_url'] : '../img/sem_foto_de_perfil.jpeg');?>" alt="Foto de perfil do usuário" class="foto_usuario">
                <h5 class="nome_usuario">Autor da Resenha: <?= htmlspecialchars($usuario);?></h5>
                <p class="bio">Bio do usuário: <?= htmlspecialchars($user_info['biografia']);?></p>
            </div>
        </footer>
    </main>
</body>
</html>