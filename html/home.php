<?php
require_once '../php/conn.php';
session_start();
$stmt = $conn->prepare("SELECT id, titulo, autor_livro, sinopse, url_imagem FROM Livros_resenha ORDER BY data DESC LIMIT 5");
if($stmt->execute()){
    $resenhas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $erro = '';
    if(!$resenhas) {
        $erro = "Nenhuma resenha encontrada. Tente novamente mais tarde.";

    }

}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Página home do clube literário Narrify - Versos e Prosa">
    <meta name="keywords" contents="home page, página home, início, Narrify, NarrifyJLA, Narrify.JLA, Narrify.jla, Narrifyjla, Narrify - Versos e Prosa">
    <meta name="author" content="Site criado por: Azazel Admetus e Kenzo_Susuna">
    <meta http-equiv="Content-Security-Policy" content="default-src 'self'">
    <meta name="robots" content="index, follow">
    <meta name="language" content="pt-BR">
    <meta name="format-detection" content="telephone=no">
    <link rel="stylesheet" href="../css/home.css">
    <title>Narrify - Versos e Prosa</title>
</head>
<body>
    <main>
        <header>
            <a href="home.php">
                <img src="../img/logo-secundária-removebg.png" alt="Logo do Clube do Livro" class="logo">
            </a>
            <nav aria-label="Barra de navegação">
                <ul>
                    <li class="">
                        <a href="perfil.php">Perfil</a>
                    </li>
                    <li class="">
                        <a href="explorar.php">Explorar</a>
                    </li>
                    <li class="">
                        <a href="loading.html?msg=Saindo...&redirect=../php/logout.php">Sair</a>
                    </li>
                </ul>
            </nav>
        </header>
        <section class="apresentacao">
            <h1>Bem-vindo ao Clube Literário Narrify - Versos e Prosa</h1>
            <p>Leia, compartilhe e descubra novas histórias com a nossa comunidade literária.</p>
            <a href="explorar.php" class="btn" aria-label="Explore as resenhas feitas pelos membros do clube">Explorar resenhas</a>
            <?php if(!empty($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] === 'admin'): ?>
            <a href="livros_resenha.php" class="btn">Publicar resenha</a>  
            <?php endif;?> 
              
        </section>
        <section class="destaques">
            <h2>Últimas resenhas</h2>
            <div class="card_resenhas">
                <?php if ($erro): ?>
                    <div class="mensagem-erro"><?= htmlspecialchars($erro); ?></div>
                <?php endif; ?>
                <div id="slider">
                    <?php foreach($resenhas as $index => $resenha): ?>
                        <article class="resenha-card slider-item <?= $index === 0 ? 'ativa' : '' ?>">
                            <img src="<?= htmlspecialchars($resenha['url_imagem']); ?>" alt="Capa do livro <?= htmlspecialchars($resenha['titulo']); ?>" class="capa-livro">
                            <div class="resenha-conteudo">
                                <h3><?= htmlspecialchars($resenha['titulo']); ?></h3>
                                <p class="autor-livro">Autor: <?= htmlspecialchars($resenha['autor_livro']); ?></p>
                                <p class="sinopse"><?= htmlspecialchars($resenha['sinopse']); ?></p>
                                <a href="resenhas.php?id=<?= htmlspecialchars($resenha['id']); ?>" class="link_resenha" aria-label="Leia a resenha completa">Leia mais</a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <section>
            <h2>Quer participar?</h2>
            <p>Atualmente, apenas membros do clube podem publicar resenhas. Fale com um administrador para saber como entrar!</p>
            <a class="link" href="mailto:narrify.jla@gmail.com" aria-label="Entre em contato com os administradores">
                <h6 class="titulo email">Fale com um administrador pelo Email: narrify.jla@gmail.com</h6>
            </a>
        </section>
    </main>
    <a href="https://www.escolajoaquimdelima.com.br/">
        <img src="../img/Logo-JLA.jpg" alt="Logo da Escola Joaquim de Lima Avelino" class="logo-jla">
    </a>
    <script>
        const urlParams = new URLSearchParams(window.location.search);
        const error = urlParams.get('processo');   
        if(error === "sucesso"){
            alert("Seja bem-vindo(a)!")
        }else if(error === "verificacao_true"){
            alert('Verificação concluída. Seja bem-vindo!')
        }
        const resenhas = document.querySelectorAll('.resenha-card');
        let index = 0;

        function mostrarProximaResenha() {
            resenhas.forEach((el, i) => {
                el.classList.remove('ativa');
            });
            resenhas[index].classList.add('ativa');
            index = (index + 1) % resenhas.length;
        }

        mostrarProximaResenha();
        setInterval(mostrarProximaResenha, 5000); 
    </script>
</body>
</html>