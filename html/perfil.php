<?php
session_start(); // inicia a sessão
if (empty($_SESSION['user_id']) || !is_numeric($_SESSION['user_id'])) { //verifica se o usuário está logado
   header('Location: login.html'); //redireciona caso não esteja
    exit();
}
require_once '../php/conn.php'; //arquivo de conexão do banco de dados
$user_id = $_SESSION['user_id'];
$erro = '';
$stmt = $conn->prepare("SELECT nome, biografia, email, imagem_perfil_url FROM users WHERE id = :user_id"); //busca as informações do usuário com base no id
$stmt->bindValue(':user_id', $user_id);
if($stmt->execute()){
    $user_info = $stmt->fetch(PDO::FETCH_ASSOC); //armazena as informações
    if(!$user_info) {
        $erro = "Erro ao buscar informações do usuário.";
        exit();
    }
} else {
    $erro = "Erro ao executar a consulta.";
    exit();
}
$username = $user_info['nome'];
//agora eu vou buscar as resenhas 
$stmt = $conn->prepare("SELECT id, titulo, url_imagem FROM Livros_resenha WHERE autor = :username ORDER BY data DESC limit 5"); //busca as 5 resenhas mais recentes do usuário
$stmt->bindValue(':username', $username);
if($stmt->execute()){
    $resenhas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $erro = "Erro ao executar a consulta de resenhas.";
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Perfil do Usuário">
    <meta name="keywords" content="perfil do usuário">
    <meta name="author" content="Site criado por: Azazel Admetus e Kenzo_Susuna">
    <meta name="robots" content="index, follow">
    <meta name="language" content="pt-BR">
    <meta name="format-detection" content="telephone=no">
    <link rel="stylesheet" href="../css/perfil.css?v=1.0">
    <title>Narrify | Perfil</title>
</head>
<body>
    <main>
        <header>
            <h1>Perfil do Usuário</h1>
            <nav>
                <ul>
                    <li><a href="home.php">Início</a></li>
                    <li><a href="loading.html?msg=Saindo...&redirect=../php/logout.php">Sair</a></li>
                </ul>
            </nav>
        </header>
        <section class="perfil-info">
            <div class="foto-perfil">
                <img id="foto-perfil" src="<?= htmlspecialchars(!empty($user_info['imagem_perfil_url']) ? $user_info['imagem_perfil_url'] : '../img/sem_foto_de_perfil.jpeg');?>" alt="Foto de perfil do usuário">
            </div>
            <div class="dados-usuario">
                <?php if ($erro): ?>
                    <div class="mensagem-erro"><?= htmlspecialchars($erro); ?></div>
                <?php endif; ?>
                <h2>Nome de Usuário: <?= htmlspecialchars($user_info['nome']); ?></h2>
                <p>Email: <?= htmlspecialchars($user_info['email']);?></p>
                <p>Biografia: <?= htmlspecialchars($user_info['biografia']);?></p>   
            </div>
        </section>
        <?php if($_SESSION['tipo_usuario'] == 'admin'): ?>
        <section class="livros-lidos">
            <h2>Resenhas feitas:</h2>
            <div class="resenha-slider">
                <?php foreach($resenhas as $index => $resenha): ?>
                    <a href="resenhas.php?id=<?= htmlspecialchars($resenha['id']); ?>">
                        <div class="slider-item<?= $index === 0 ? ' ativa' : '' ?>">
                            <img src="<?= htmlspecialchars($resenha['url_imagem']); ?>" alt="Capa do livro">
                            <h3><?= htmlspecialchars($resenha['titulo']); ?></h3>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif;?>
        <section id="config-user">
            <a href="alterar-username.html" aria-label="Altere seu nome de usuário">
                <h4>Altere seu nome de usuário</h4>
            </a>
            <a href="inserir_imagem_perfil.html" aria-label="Altere sua foto de perfil">
                <h4>Altere sua foto de perfil</h4>
            </a>
            <a href="../php/redirecionador.php?fluxo=perfil" aria-label="Altere sua senha">
                <h4>Altere sua senha</h4>
            </a>
            <a href="alterar_biografia.html" aria-label="Altere sua biogragia">
                <h4>Altere sua Biografia</h4>
            </a>
        </section>
        <footer>
            <p>© 2025 Narrify - Clube do Livro</p>
        </footer>
    </main>
    <script>
        //fazer o esquema de slides para mostrar as resenhas do usuário
        let index = 0;
        const slides = document.querySelectorAll('.slider-item');
        function mostrarProximaResenha() {
            slides[index].classList.remove('ativa');
            index = (index + 1) % slides.length;
            slides[index].classList.add('ativa');
        }
        setInterval(mostrarProximaResenha, 4000);
    </script>
</body>
</html>