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
    <link rel="stylesheet" href="../css/cadastroLivros.css">
    <title>Cadastro dos livros recomendados</title>
</head>
<body>
    <header>
        <a href="home.php">
            <img src="../img/logo-principal-slogan-transparente.png" alt="Logo do clube do livro">
        </a>
    </header>
    <form action="../php/cadastroLivros.php" method="POST" enctype="multipart/form-data">
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
</body>
</html>