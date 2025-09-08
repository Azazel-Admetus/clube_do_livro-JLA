<?php
require "conn.php";
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    // vamos pegar as variáveis
    //estas são sobre o estudante
    $nomeEstudante = trim($_POST['nome_estudante']);
    $turno = strip_tags(trim($_POST['turno']));
    $serie = strip_tags(trim($_POST['serie']));
    // agora estas são sobre o livro
    $nomeLivro = strip_tags(trim($_POST['nome_livro']));
    $autorLivro = strip_tags(trim($_POST['autor_livro']));
    $resenha = strip_tags(trim($_POST['resenha_livro']));
    // agora vamos para subir no banco de dados
    $stmt = $conn->prepare("INSERT INTO inscricao (aluno, turno, serie, NomeLivro, AutorLivro, Resenha) VALUES (:aluno, :turno, :serie, :NomeLivro, :AutorLivro, :Resenha)");
    $stmt->bindParam(":aluno", $nomeEstudante);
    $stmt->bindParam(":turno", $turno);
    $stmt->bindParam(':serie', $serie);
    $stmt->bindParam(':NomeLivro', $nomeLivro);
    $stmt->bindParam(':AutorLivro', $autorLivro);
    $stmt->bindParam(':Resenha', $resenha);
    if($stmt->execute()){
        header('Location:../html/home.php?inscricao=concluida');
        exit();
    } else{
        header('Location:../html/home.php?inscricao=erro');
        exit();
    }
}

?>

