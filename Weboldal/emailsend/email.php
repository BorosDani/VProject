<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';
require 'phpmailer/Exception.php';

function sendEmail($recipient, $subject, $body) {
    $mail = new PHPMailer(true);
    
    try {
        $mail->isSMTP();
        $mail->Host = 'mail.villammelo.hu';
        $mail->SMTPAuth = true;
        $mail->Username = 'noreply@villammelo.hu';
        $mail->Password = '58P.t$NF@Gv@{MgR';
        
        if ($mail->Port == 465) {
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        } else {
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        }
        $mail->Port = 587;
        
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
        
        $mail->setFrom('noreply@villammelo.hu', 'VillamMelo.hu');
        $mail->addAddress($recipient);
        $mail->addReplyTo('info@villammelo.hu', 'Információ');

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->AltBody = strip_tags($body);
        $mail->CharSet = 'UTF-8';
        
        $mail->send();
        return true;
        
    } catch (Exception $e) {
        error_log("Email küldési hiba: " . $mail->ErrorInfo);
        return "Hiba: " . $mail->ErrorInfo;
    }
}

/*
// Hibakezelés bekapcsolása a teszteléshez
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Használat példa

$to = "boros.daniel2003@gmail.com";
$subject = "Teszt Email";
$message = "
    <h1>Üdvözöljük!</h1>
    <p>Ez egy teszt email a PHPMailer segítségével.</p>
    <p><strong>Küldés ideje:</strong> " . date('Y-m-d H:i:s') . "</p>
";

$result = sendEmail($to, $subject, $message);

if ($result === true) {
    echo "Email sikeresen elküldve!";
} else {
    echo "Hiba történt: " . $result;
}
*/
?>