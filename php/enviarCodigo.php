<?php
require_once __DIR__ . '/../vendor/autoload.php';//puxa o arquivo do vendor para usar a biblioteca
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
//função para enviar código pelo email
function enviarCodigo($email, $codigo){
    $mail = new PHPMailer(true);
    try{
        //$mail->SMTPDebug = 4; só quando for debugar
        //$mail->Debugoutput = 'html';
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['EMAIL_USERNAME'];
        $mail->Password = $_ENV['EMAIL_PASSWORD']; 
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
        $mail->setFrom($_ENV['EMAIL_USERNAME'], $_ENV['EMAIL_FROM_NAME']);
        $mail->addAddress($email);
        $mail->Subject = 'Código de Verificação';
        $mail->CharSet = 'UTF-8';
        $mail->Body = "Seu código de verificação é: $codigo";
        $mail->send();
        return true;
    } catch(Exception $e) {
        echo "Erro ao enviar email: " . $mail->ErrorInfo;
        return false;
    }
}
?>