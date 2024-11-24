<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'partage.univ-eiffel.fr';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'damien.tremerie@edu.univ-eiffel.fr';                     //SMTP username
    $mail->Password   = 'L3cdde1emdpsD.';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('damien.tremerie@edu.univ-eiffel.fr', 'Silver Economy');
    $mail->addAddress($_SESSION['email']);     //Add a recipient

    $mail->SMTPDebug = 0;
    
    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = $_SESSION["objet"];
    $mail->Body    = $_SESSION["message"];
    $mail->AltBody = $_SESSION["message"];

    $mail->send();
} catch (Exception $e) {
}