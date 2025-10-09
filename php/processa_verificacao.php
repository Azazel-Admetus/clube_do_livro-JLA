<?php
require "conn.php";
session_start();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../html/login.html');
    exit();
}
$codigo = trim($_POST['codigo'] ?? '');
$email = $_SESSION['email_autenticacao'] ?? null;
$fluxo = $_SESSION['fluxo_autenticacao'] ?? null;
if (!$email || !$codigo) {
    header('Location: ../html/verificar_codigo.html?error=campos');
    exit();
}

if ($fluxo === 'perfil' || $fluxo === 'esqueci_senha') {
    header('Location:../html/loading.html?msg=Redirecionando...&redirect=../html/alterar_senha.html');
    exit();
}

if($fluxo == 'perfil' || $fluxo == 'esqueci_senha'){
    header('Location:../html/loading.html?msg=Redirecionando...&redirect=../html/alterar_senha.html');
    exit;
}
$stmt = $conn->prepare("
    SELECT id, codigo_verificacao, codigo_expira 
    FROM users 
    WHERE email = :email LIMIT 1
");
$stmt->execute([':email' => $email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header('Location:../html/verificar_codigo.html?error=usuario_nao_encontrado');
    exit();
}

if (!empty($user['codigo_expira']) && strtotime($user['codigo_expira']) < time()) {
    header('Location:../html/verificar_codigo.html?error=codigo_expirado');
    exit();
}

// comparação segura
if (hash_equals((string)$user['codigo_verificacao'], (string)$codigo)) {
    $stmt2 = $conn->prepare("UPDATE users SET verificado = 1, codigo_verificacao = NULL, codigo_expira = NULL WHERE id = :id");
    if ($stmt2->execute([':id' => $user['id']])) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['nome'] ?? null;
        unset($_SESSION['email_autenticacao'], $_SESSION['fluxo_autenticacao']);
        header('Location:../html/loading.html?msg=Verificando...&redirect=../html/home.php?processo=verificacao_true');
        exit();
    }
}

header('Location:../html/verificar_codigo.html?error=codigo_incorreto');
exit();
?>