<?php
session_start();

if(isset($_GET['fluxo'])){
    $fluxo = $_GET['fluxo'];
    if(in_array($fluxo, ['login', 'perfil'])){
        $_SESSION['fluxo_autenticacao'] = $fluxo;
        header('Location:../html/autenticacao.html');
        exit;
    } else {
        header('Location:../html/home.html?error=fluxo_invalido');
        exit;
    }
}
