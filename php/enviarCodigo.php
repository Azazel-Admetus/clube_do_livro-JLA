<?php
require_once __DIR__ . '/../vendor/autoload.php';//puxa o arquivo do vendor para usar a biblioteca
require "error_log.php";
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
//função para enviar código pelo email
function enviarCodigo($email, $codigo){
    $mail = new PHPMailer(true);
    try{
        // --- DEBUG TEMPORÁRIO (REMOVER depois) ---
        // nível 4 = mais verboso; depois baixamos para 0
        $mail->SMTPDebug = 4;
        // envia o debug para o log de erros do PHP (não para o browser)
        $mail->Debugoutput = function($str, $level) {
            error_log("PHPMailer debug [level $level]: $str");
        };
        // ------------------------------------------
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['EMAIL_USERNAME'];
        $mail->Password = $_ENV['EMAIL_PASSWORD']; 
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
        //habilitar somente em ambiente de desenvolvimento. Deve-se deixar inabilitado em ambiente de produção 
        $mail->SMTPOptions = array(
            'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
            )
        );

        $mail->setFrom($_ENV['EMAIL_USERNAME'], $_ENV['EMAIL_FROM_NAME']);
        $mail->addAddress($email);
        $mail->Subject = 'Código de Verificação';
        $mail->CharSet = 'UTF-8';
        $mail->Body = "Seu código de verificação é: $codigo";
        $mail->send();
        return true;
    } catch(Exception $e) {
        // registra info detalhada no error_log para eu analisar
        error_log("PHPMailer Exception: " . $e->getMessage());
        error_log("PHPMailer ErrorInfo: " . $mail->ErrorInfo);
        // também retorna uma mensagem leve para o browser (sem dados sensíveis)
        echo "Erro ao enviar email: " . $mail->ErrorInfo;
        return false;
    }
}
?>