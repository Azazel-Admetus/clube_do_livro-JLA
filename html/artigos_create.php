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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Crie artigos">
    <meta name="keywords" content="artigos, criar artigos, crie artigos, artigos literários, informação">
    <meta name="author" content="Site criado por: Azazel Admetus">
    <meta name="robots" content="index, follow">
    <meta name="language" content="pt-BR">
    <meta name="format-detection" content="telephone=no">
    <link rel="icon" href="../img/logo_favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="../css/artigos_create.css">
    <title>Narrify - Versos e Prosa</title>
</head>
<body>
    <header>
        <img src="../img/logo-secundária-removebg.png" alt="Logo do site Narrify - Versos e Prosa">
        <a href="home.php" class="link">Início</a>
    </header>
    <form action="../php/artigos_create.php" method="POST">
        <div class="info">
            <h1>Crie artigos</h1>
            <p>Fale sobre livros, autores, figuras históricas... use as palavras e repasse seu conhecimento adiante.</p>
            <p>Se atente com o conteúdo do seu artigo. Se certifique de passar uma ideia objetiva, de ter uma escrita clara e coerente. Opte pelo uso de uma linguagem mais culta.</p>
            <p>Após enviar o seu artigo, ele passará por um processo de análise. Em até 7 dias úteis você receberá um email avisando se foi aprovada ou se carece de mais aprimorações.</p>
        </div>
        <div class="form">
            <label for="titulo">Título</label>
            <input type="text" id="titulo" name="titulo" required placeholder="Defina o Título do artigo.">
            <label for="descricao">Descrição</label>
            <input type="text" id="descricao" name="descricao" required placeholder="Seja curto, objetivo e direto ao ponto. Tente captar a atenção do leitor.">
            <label for="tags">Tags</label>
            <input type="text" id="tags" name="tags" required placeholder="Adicionei tags, ex.: romance, fantasia, escolar">
            <label for="conteudo">Conteúdo</label>
            <textarea id="conteudo" name="conteudo" rows="30" required placeholder="Defina o conteúdo do artigo. Se atente em fazê-lo de forma completa, de preferência em uma linguagem mais culta."></textarea>
            <footer>
                <button type="submit">Publicar</button>
            </footer>
        </div>
    </form>
    <a href="https://www.escolajoaquimdelima.com.br/">
        <img src="../img/Logo-JLA.jpg" alt="Logo da Escola Joaquim de Lima Avelino" class="logo-jla">
    </a>
    <script>
        //variáveis para tratamento de erros
        const urlParams = new URLSearchParams(window.location.search);
        const insert = urlParams.get('insert');
        const error = urlParams.get('error');
        const erros = ['error', 'insertArtigo', 'empty'];

        // faz a verificação do valor da variável
        if (insert == 'true') {
            alert('Artigos criados com sucesso.');
        }else if (erros.includes(error)){
            alert(`Ocorreu um erro: ${erro}. Tente novamente`);
        }
    </script>
</body>
</html>