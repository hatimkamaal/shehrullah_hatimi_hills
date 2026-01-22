<?php

// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\Exception;

// require 'vendor/phpmailer/Exception.php';
// require 'vendor/phpmailer/PHPMailer.php';
// require 'vendor/phpmailer/SMTP.php';

class EmailService
{
    public static function sendEmail(Dao $dao)
    {

        $email = $dao->email;


        $mail = new PHPMailer;
        $mail->CharSet = 'UTF-8';
        $mail->isSMTP();
        $mail->SMTPDebug = 2; // 0 = off (for production use) - 1 = client messages - 2 = client and server messages
        $mail->Host = "smtp.gmail.com"; // use $mail->Host = gethostbyname('smtp.gmail.com'); // if your network does not support SMTP over IPv6
        $mail->Port = 587; // TLS only
        $mail->SMTPSecure = 'tls'; // ssl is depracated
        $mail->SMTPAuth = true;
        $mail->Username = 'hatim.kamaal@gmail.com';
        $mail->Password = 'urhh gruf hhkx isww';
        $mail->setFrom('hatim.utube@gmail.com', 'Hatim Kamaal');
        $mail->addAddress('hatim.kamal@team.telstra.com', 'Hatim Kamal');
        $mail->Subject = 'PHPMailer GMail SMTP test';
        $mail->isHTML(true);
        $mail->msgHTML("test body"); //$mail->msgHTML(file_get_contents('contents.html'), __DIR__); //Read an HTML message body from an external file, convert referenced images to embedded,
        $mail->AltBody = 'HTML messaging not supported';
        // $mail->addAttachment('images/phpmailer_mini.png'); //Attach an image file

        if (!$mail->send()) {
            echo "Mailer Error: " . $mail->ErrorInfo;
        } else {
            echo "Message sent!";
        }


    }

}