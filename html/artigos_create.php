<?php
require_once "../php/conn.php";
session_start();
$userID = $_SESSION['user_id'];
if($userID == null){
    header('Location:login.html');
    exit();
}else{
    //vamos simplesmente buscar no banco de dados e verificar se ele tem permissão para criar esses artigos
    $stmt = $conn->prepare("SELECT tipo_usuario FROM users WHERE id = :id");
    $stmt->bindParam(":id", $userID);
    $stmt->execute();
    $tipo_user = $stmt->fetch(PDO::FETCH_ASSOC);
    if($tipo_user['tipo_usuario'] != "admin"){
        header('Location:home.php?user=nao_autorizado');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Narrify - Versos e Prosa</title>
</head>
<body>
    <header>
        <img src="../static/img/logo.png" alt="Logo do site Narrify - Versos e Prosa">
    </header>
    <form action="../php/artigos_create.php" method="POST">
        <div class="info">
            <h1>Crie artigos</h1>
            <p>Fale sobre livros, autores, figuras históricas... use as palavras e repasse seu conhecimento adiante.</p>
            <p>Se atente com o conteúdo do seu artigo. Se certifique de passar uma ideia objetiva, de ter uma escrita clara e coerente. Opte pelo uso de uma linguagem mais culta.</p>
            <p>Após enviar o seu artigo, ele passará por um processo de análise. Em até 7 dias úteis você receberá um email avisando se foi aprovada ou se carece de mais aprimorações.</p>
        </div>
        <div class="form">
            <h2>Crie um artigo</h2>
            <label for="titulo">Título</label>
            <input type="text" id="titulo" name="titulo" required placeholder="Defina o Título do artigo.">
            <label for="descricao">Descrição</label>
            <input type="text" id="descricao" name="descricao" required placeholder="Seja curto, objetivo e direto ao ponto. Tente captar a atenção do leitor.">
            <label for="tags">Tags</label>
            <input type="text" id="tags" name="tags" required placeholder="Adicionei tags, ex.: romance, fantasia, escolar">
            <label for="conteudo">Conteúdo</label>
            <textarea id="conteudo" name="conteudo" rows="30" required placeholder="Defina o conteúdo do artigo. Se atente em fazê-lo de forma completa, de preferência em uma linguagem mais culta."></textarea>
            <button type="submit">Publicar</button>
        </div>
    </form>
</body>
</html>