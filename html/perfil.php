<?php
session_start();
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['user_id'] == '') {
    header('Location:login.html');
    exit();
}
require_once '../php/conn.php';
$user_id = $_SESSION['user_id'];
$erro = '';
$stmt = $conn->prepare("SELECT nome, biografia, email, imagem_perfil_url FROM users WHERE id = :user_id");
$stmt->bindValue(':user_id', $user_id);
if($stmt->execute()){
    $user_info = $stmt->fetch(PDO::FETCH_ASSOC);
    if(!$user_info) {
        $erro = "Erro ao buscar informações do usuário.";
        exit();
    }
} else {
    $erro = "Erro ao executar a consulta.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Narrify | Perfil</title>
    <link rel="stylesheet" href="../css/perfil.css">
</head>

<body>
    <main>
        <header>
            <h1>Perfil do Usuário</h1>
            <nav>
                <ul>
                    <li><a href="home.html">Início</a></li>
                    <li><a href="../php/logout.php">Sair</a></li>
                </ul>
            </nav>
        </header>

        <section class="perfil-info">
            <div class="foto-perfil">
                <img id="foto-perfil" src="<?= htmlspecialchars($user_info['imagem_perfil_url']);?>" alt="Foto do usuário">
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

        <section class="livros-lidos">
            <h2>Livros lidos:</h2>
            <div id="card-livros">
                <h3 id="titulo-livro">Título do livro</h3>
                <img id="imagem-livro" src="#" alt="Capa do livro">
            </div>
        </section>

        <section id="config-user">
            <a href="alterar-username.html">
                <h4>Altere seu nome de usuário</h4>
            </a>
            <a href="inserir_imagem_perfil.html">
                <h4>Altere sua foto de perfil</h4>
            </a>
            <a href="../php/redirecionador.php?fluxo=perfil">
                <h4>Altere sua senha</h4>
            </a>
            <a href="alterar_biografia.html">
                <h4>Altere sua Biografia</h4>
            </a>
        </section>

        <footer>
            <p>© 2025 Narrify - Clube do Livro</p>
        </footer>
    </main>
</body>

</html>