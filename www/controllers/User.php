<?php

namespace App\controllers;

use App\core\User as Us;
use App\core\View;
use App\models\User as UserModel;
use App\core\Verificator;
use App\models\PasswordReset;
use App\PHPMailer\PHPMailer;
use App\PHPMailer\SMTP;
use App\PHPMailer\Exception;

class User 
{
    public function login()
    {
        $user = new UserModel();

        if(!empty($_POST)) {
            $result = Verificator::checkForm($user->getLoginForm(), $_POST);

            print_r($result);
        }

        $view = new View("Login");
        $view->assign("user", $user);
    }

    public function register()
    {
        $user = new UserModel();

        if(!empty($_POST)) {
            $result = Verificator::checkForm($user->getLoginForm(), $_POST);
        }

        // var_dump($result);
        // echo "<br>";
        // var_dump($_POST);
        $view = new View("Register");
        $view->assign("user", $user);
    }

    public function logout()
    {
        $user = new UserModel();
        $user = $user->setId(1);
        $passwordReset = new PasswordReset();
        $passwordReset->generateToken();
        $passwordReset->generateTokenExpiry();
        $passwordReset->setUserId($user);
        // echo "<pre>";
        // var_dump($passwordReset);
        // echo "</pre>";
        $passwordReset->save();
    }

    public function forgetPassword(){
        $user = new UserModel();
        $view = new View("forgetPassword");
        $view->assign("user", $user);
    }

    public function sendPasswordReset()
    {
        $user = new UserModel();
        if(!empty($_POST)) {
            $result = Verificator::checkForm($user->getForgetPasswordForm(), $_POST);
            if(empty($result)){
                $user = $user->getOneBy(["email" => $_POST['email']])[0];
                if(!empty($user)){
                    // echo "<pre>";
                    // var_dump($user);
                    // echo "</pre>";
                    $passwordReset = new PasswordReset();
                    $passwordReset->generateToken();
                    $passwordReset->generateTokenExpiry();
                    $passwordReset->setUserId($user);
                    // var_dump($passwordReset);

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
                    $mail->addAddress($_POST['email']);
                    $mail->Subject = "Test";
                    $mail->Body = '<h1 style="color:blue">FoodPressCMS</h1>
                        <p>
                            Nous avons bien reçu votre demande de changement de mot de passe.
                        </p>
                        <div>
                            Changez de mot de passe en cliquant <a href="http://localhost:81/changePassword?token=' . $passwordReset->getToken() . '">ici</a>
                        </div>
                    ';
                    if($mail->Send()){
                        echo "Email envoyé";
                        $passwordReset->save();
                    }else{
                        echo "Une erreur ";
                    }
                    $mail->smtpClose();

                }else{
                    echo "existe pas";
                }
            }else{
                $view = new View("forgetPassword");
                $view->assign("error", "Email invalide. =,(");
                $view->assign("user", $user);
            }
        }else{
            $view = new View("forgetPassword");
            $view->assign("error", "Un champ a disparu. =,(");
            $view->assign("user", $user);
        }
    }

    public function changePassword(){
        $passwordReset = new PasswordReset();
        $user = new UserModel();
        if(!empty($passwordReset->getOneBy(["token" => $_GET["token"]])[0])){
            $passwordReset = $passwordReset->getOneBy(["token" => $_GET["token"]])[0];
            if($passwordReset->getTokenExpiry() > time()){
                $view = new View("changePassword");
                $view->assign("user", $user);
            }else{
                echo '<p style="color:red;">Le token n\'est plus valide</p>';
            }
        }else{
            echo '<p style="color:red;">Le token n\'existe pas</p>';
        }
    }

    public function confirmPasswordChangement(){
        $user = new UserModel();
        if(!empty($_POST)) {
            $result = Verificator::checkForm($user->getChangePasswordForm(), $_POST);
        }
        // var_dump($result);
    }

    public function confirmAccount() {
        $user = new UserModel();

        if(!empty($_POST)) 
        {
            $result = Verificator::checkForm($user->getRegisterForm(), $_POST);

            if(empty($result)) {
                $user->setFirstname($_POST['firstname']);
                $user->setLastname($_POST['lastname']);
                $user->setEmail($_POST['email']);
                $user->setPassword($_POST['password']);
                $user->generateToken();

                $user->save();

                echo "Succès";
            }
        }
    }

    public function connection(){
        $user = new UserModel();
        $password = $_POST['password'];
        $email = $_POST['email'];
        $user->setPassword($password);
        $user->setEmail($email);
    }
}