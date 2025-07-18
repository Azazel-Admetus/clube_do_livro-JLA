<?php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['user_id'] == '') {
    header('Location:login.html');
    exit();
}
require_once '../php/conn.php';
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare('SELECT tipo_usuario FROM users WHERE id = :id');
$stmt->bindValue(':id', $user_id);
if($stmt->execute()){
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if($user && $user['tipo_usuario'] != 'admin') {
        header('Location:home.html?user=nao_autorizado');
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
    <title>Livros resenha</title>
    <link rel="stylesheet" href="../css/livros_resenha.css">
</head>

<body>
    <header>
        <a href="home.php">
            <img src="../img/logo-secundária-removebg.png" alt="Logo">
        </a>
    </header>

    <main>
        <h1>Resenha de Livros</h1>
        <p>Crie resenhas de livros para postar. Mostre como foi sua leitura e interpretação do livro</p>

        <form action="../php/livros_resenha.php" method="post" enctype="multipart/form-data">
            <label for="titulo">Título do Livro:</label>
            <input type="text" id="titulo" name="titulo" required placeholder="Digite o título do livro">

            <label for="autor">Autor do Livro:</label>
            <input type="text" id="autor" name="autor" required placeholder="Digite o autor do livro">

            <label for="genero">Gênero do Livro:</label>
            <input type="text" id="genero" name="genero" required placeholder="Digite o gênero do livro">

            <label for="sinopse">Sinopse:</label>
            <input type="text" id="sinopse" name="sinopse" required placeholder="Escreva uma breve sinopse">

            <label for="resenha">Crie sua resenha:</label>
            <textarea id="resenha" name="resenha" rows="5" placeholder="Escreva sua resenha aqui..."
                required></textarea>

            <label for="capa" class="custom-file-label">Imagem da capa do livro</label>
            <input type="file" id="capa" name="capa" accept=".jpg, .jpeg, .png" required>
            <span id="nome-capa">Nenhum arquivo selecionado</span>

            <button type="submit">Salvar</button>
        </form>
    </main>

    <script>
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