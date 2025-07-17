<?php
require_once '../php/conn.php';
$stmt = $conn->prepare("SELECT id, titulo, autor_livro, sinopse FROM Livros_resenha");
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
    <title>Narrify - Versos e Prosa</title>
</head>
<body>
    <main>
        <header>
            <h1></h1>
            <nav>
                <ul>
                    <li class="">
                        <a href="perfil.php">Perfil</a>
                    </li>
                    <li class="">
                        <a href="../php/logout.php">Sair</a>
                    </li>
                    <li class="">
                        <a href="explorar.php">Explorar</a>
                    </li>
                </ul>
            </nav>
        </header>
        <section class="apresentacao">
            <h1>Bem-vindo ao Clube Literário Narrify - Versos e Prosa</h1>
            <p>Leia, compartilhe e descubra novas histórias com a nossa comunidade literária.</p>
            <a href="explorar.php" class="btn">Explorar resenhas</a>                  
        </section>
        <section class="destaques">
            <h2>Últimas resenhas</h2>
            <div class="card_resenhas">
                <?php if ($erro): ?>
                    <div class="mensagem-erro"><?= htmlspecialchars($erro); ?></div>
                <?php endif; ?>
                <!-- <?php foreach($resenhas as $resenha): ?> -->
                    <article class="resenha">
                        <h2 class="titulo"><?= htmlspecialchars($resenha['titulo']);?></h2>
                        <h6 class="titulo">Autor: <?= htmlspecialchars($resenha['autor_livro']) ?></h6>
                        <p class="descricao"><?= htmlspecialchars($resenha['sinopse']);?></p>
                        <a href="resenhas.php?id=<?= htmlspecialchars($resenha['id']);?>" class="link_resenha">Leia mais</a>
                    </article>
              <!--  <?php endforeach; ?> -->
            </div>
        </section>
        <section>
            <h2>Quer participar?</h2>
            <p>Atualmente, apenas membros do clube podem publicar resenhas. Fale com um administrador para saber como entrar!</p>
            <a class="link" href="mailto:narrify.jla@gmail.com">
                <h6 class="titulo">Fale com um administrador pelo Email: narrify.jla@gmail.com</h5>
            </a>
        </section>
        <footer>
            <img src="../img/Logo-JLA.jpg" alt="Logo da Escola Joaquim de Lima Avelino">
        </footer>
    </main>
    <script>
        const urlParams = new URLSearchParams(window.location.search);
        const error = urlParams.get('processo');   
        if(error === "sucesso"){
            alert("Seja bem-vindo(a)!")
        }
    </script>
</body>
</html>