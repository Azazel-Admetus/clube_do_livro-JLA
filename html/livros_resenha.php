<?php
session_start(); //inicia a sessão
if(!isset($_SESSION['user_id']) || $_SESSION['user_id'] == '') { //verifica se o usuário está logado
    header('Location:login.html');
    exit();
}
require_once '../php/conn.php'; //arquivo de conexão do banco de dados
$user_id = $_SESSION['user_id']; //pega o id do usuário na sessão
$stmt = $conn->prepare('SELECT tipo_usuario FROM users WHERE id = :id'); //busca o tipo do usuário com base no seu id
$stmt->bindValue(':id', $user_id);
if($stmt->execute()){
    $user = $stmt->fetch(PDO::FETCH_ASSOC); //pega o valor do banco de dados
    if($user && $user['tipo_usuario'] != 'admin') { //verifica se é administrador
        header('Location:home.html?user=nao_autorizado'); //redireciona com parâmetro para tratamento de erro
        exit();
    }
}else{
    die("erro ao identificar usuário");
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Crie resenhas sobre diversos livros">
    <meta name="keywords" content="crie resenha, criar resenhas, resenhas">
    <meta name="author" content="Site criado por: Azazel Admetus e Kenzo_Susuna">
    <meta name="robots" content="index, follow">
    <meta name="language" content="pt-BR">
    <meta name="format-detection" content="telephone=no">
    <link rel="stylesheet" href="../css/livros_resenha.css">
    <title>Livros resenha</title>
</head>
<body>
    <header>
        <a href="home.php">
            <img src="../img/logo-secundária-removebg.png" alt="Logo do clube do livro Narrify">
        </a>
    </header>
    <main>
        <h1>Resenha de Livros</h1>
        <p>Crie resenhas de livros para postar. Mostre como foi sua leitura e interpretação do livro</p>
        <!-- formulário para criar a resenha -->
        <form action="../php/livros_resenha.php" method="post" enctype="multipart/form-data">
            <label for="titulo">Título do Livro:</label>
            <input type="text" id="titulo" name="titulo" required placeholder="Digite o título do livro">
            <!-- espaço para colocar o nome do autor do livro -->
            <label for="autor">Autor do Livro:</label>
            <input type="text" id="autor" name="autor" required placeholder="Digite o autor do livro">
            <!-- espaço para colocar o gênero do livro -->
            <label for="genero">Gênero do Livro:</label>
            <input type="text" id="genero" name="genero" required placeholder="Digite o gênero do livro">
            <!-- espaço para colocar a sinopse do livro -->
            <label for="sinopse">Sinopse:</label>
            <input type="text" id="sinopse" name="sinopse" required placeholder="Escreva uma breve sinopse">
            <!-- espaço para criar a resenha -->
            <label for="resenha">Crie sua resenha:</label>
            <textarea id="resenha" name="resenha" rows="5" placeholder="Escreva sua resenha aqui..."required></textarea>
            <!-- espaço para fazer o upload do arquivo da capa do livro -->
            <label for="capa" class="custom-file-label">Imagem da capa do livro</label>
            <input type="file" id="capa" name="capa" accept=".jpg, .jpeg, .png" required>
            <span id="nome-capa">Nenhum arquivo selecionado</span>
            <!-- botão para enviar requisição -->
            <button type="submit" aria-label="Enviar alterações">Salvar</button>
        </form>
    </main>
    <script>
        //variáveis para tratamento de erro e verificação
        const urlParams = new URLSearchParams(window.location.search);
        const error = urlParams.get('error');   
        if(error === "success"){
            alert("Salvado com sucesso!")
        }else if(error === "invalid_type"){
            alert('Tipo de arquivo inválido. Tente novamente.')
        }else if(error === "size"){
            alert("Tamanho do arquivo excede os limites. Tente novamente.")
        }else if(error === "not_image"){
            alert("Tipo de arquivo inválido. Tente novamente.")
        }else if(error === "db_update_failed"){
            alert("Falha ao salvar resenha. Tente novamente.")
        }
        const capaInput = document.getElementById('capa');
        const nomeCapa = document.getElementById('nome-capa');
        capaInput.addEventListener('change', () => {
            const arquivo = capaInput.files[0];
            nomeCapa.textContent = arquivo ? arquivo.name : 'Nenhum arquivo selecionado';
        });
    </script>
</body>
</html>