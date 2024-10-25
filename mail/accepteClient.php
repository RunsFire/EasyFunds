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
    $mail->Password   = 'L3cddeuemdpsD.';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('damien.tremerie@edu.univ-eiffel.fr', 'Silver Economy');
    $mail->addAddress($_SESSION['email']);     //Add a recipient

    $mail->SMTPDebug = 0;
    
    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Confirmation de la creation de votre compte';
    $mail->Body    = 'Bonjour, <br>Nous vous confirmons que votre compte a bien ete creer. Vous pouvez des a present vous connecter en cliquant <a href=\'https://etudiant.u-pem.fr/~damien.tremerie/SilverEconomy/login.php\'>ici</a>. <br> Nous vous remercions de votre confiance et vous souhaitons une bonne journee';
    $mail->AltBody = 'Bonjour, <br>Nous vous confirmons que votre compte a bien ete creer. Vous pouvez des a present vous connecter en cliquant <a href=\'https://etudiant.u-pem.fr/~damien.tremerie/SilverEconomy/login.php\'>ici</a>. <br> Nous vous remercions de votre confiance et vous souhaitons une bonne journee';

    $mail->send();
} catch (Exception $e) {
}