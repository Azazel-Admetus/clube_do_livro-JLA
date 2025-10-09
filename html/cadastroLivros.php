<?php
require_once "../php/conn.php";
session_start();
$userID = $_SESSION['user_id'];
if($userID == null){
    header('Location:login.html');
    exit();
}else{
    //vamos simplesmente buscar no banco de dados e verificar se ele tem permissão para criar esses artigos
    $stmt = $conn->prepare("SELECT cargo FROM users WHERE id = :id");
    $stmt->bindParam(":id", $userID);
    $stmt->execute();
    $tipo_user = $stmt->fetch(PDO::FETCH_ASSOC);
    if($tipo_user['cargo'] != "Presidente"){
        header('Location:home.php?user=nao_autorizado');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="p-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Cadastrar livros recomendados">
    <meta name="keywords" content="livros, cadastro de livros, cadastro, cadastrar livros, livros recomendados, recomendados">
    <meta name="author" content="Site criado por: Azazel Admetus">
    <meta name="robots" content="index, follow">
    <meta name="language" content="pt-BR">
    <meta name="format-detection" content="telephone=no">
    <link rel="icon" href="../img/logo_favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="../css/cadastroLivros.css">
    <title>Cadastro dos livros recomendados</title>
</head>
<body>
    <header>
        <a href="home.php">
            <img src="../img/logo-principal-slogan-transparente.png" alt="Logo do clube do livro">
        </a>
    </header>
    <form action="../php/CadastroLivros.php" method="POST" enctype="multipart/form-data">
        <h1>Cadastre os livros recomendados</h1>
        <label for="name_livro">Nome do Livro</label>
        <input type="text" name="nome_livro" id="nome_livro" required placeholder="Digite o nome do livro a ser cadastrado">
        <label for="capa_livro">Faça o upload da capa do livro</label>
        <input type="file" name="capa_livro" id="capa_livro" accept=".jpg, .jpeg, .png" required>
        <label for="tags">Tags</label>
        <input type="text" id="tags" name="tags" required placeholder="Exemplos: romance, comédia, ação, ficção...">
        <label for="link_avaliado">Insira o link de afiliado</label>
        <input type="text" id="link_avaliado" name="link" required>
        <button type="submit">Salvar</button>
    </form> 
    <a href="https://www.escolajoaquimdelima.com.br/">
        <img src="../img/Logo-JLA.jpg" alt="Logo da Escola Joaquim de Lima Avelino" class="logo-jla">
    </a>
    <script>
        //variáveis para tratamento de erros
        const urlParams = new URLSearchParams(window.location.search);
        const error = urlParams.get('error');
        const erros = ['invalid_type', 'size', 'not_image', 'db_update_failed', 'upload_failed'];

        // faz a verificação do valor da variável
        if (error == 'sucess') {
            alert('Artigos criados com sucesso.');
        }else if (erros.includes(error)){
            alert(`Ocorreu um erro: ${erro}. Tente novamente`);
        }
    </script>
</body>
</html>