<?php

namespace App\controllers;


use App\PHPMailer\PHPMailer;
use App\PHPMailer\SMTP;
use App\PHPMailer\Exception;

class Mail
{
    public function main($mail)
    {
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = MAILHOST;
        $mail->SMTPAuth = "true";
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = MAILPORT;
        $mail->Username = MAILUSER;
        $mail->Password = MAILPSWD;
        $mail->iSHtml(true);
        $mail->setFrom(MAILUSER);
        $mail->addAddress("$mail");
        $mail->Subject = "FoodPress - Confirmation d\'inscription";
        $mail->Body = 'Bonjour ceci est le lien de notre site <a href="https://desolate-earth-34955-a0697a407f9b.herokuapp.com/">ici</a>';
        if($mail->Send()){
            echo "Email envoyé";
        }else{
            echo "Une erreur ";
        }
        $mail->smtpClose();
    }
}