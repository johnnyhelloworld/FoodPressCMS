<?php

namespace App\controllers;
session_start();

use App\core\User as Us;
use App\core\View;
use App\models\User as UserModel;
use App\core\Verificator;
use App\models\PasswordReset;
use App\PHPMailer\PHPMailer;
use App\PHPMailer\SMTP;
use App\PHPMailer\Exception;
use App\core\Session;
use App\core\Sql;
use App\controllers\Mail;

class User 
{
    public function login()
    {
        $view = new View("Login");
        $user = new UserModel();
        $config = $user->getLoginForm();
        $view->assign("user", $user);
        if($_SERVER["REQUEST_METHOD"] == "POST") {
            $result = Verificator::checkForm($user->getLoginForm(), $_POST);

            if(!empty($result)) {
                $view->assign('result', $result);
            }else{
                if(!empty($_POST)) {
                    $user_form = $user->getOneBy(['email' => $_POST['email']]);
                    $object = $user_form[0];

                    !is_null($user_form) ? $password = $object->password : '';
                    !is_null($user_form) ? $email = $object->email : '';

                    $token = $object->token;

                    $password_user = password_hash(isset($password) ? $password : '', PASSWORD_DEFAULT);
                    $email_user = isset($email) ? $email : '';
                    $password_verification = password_verify($_POST['password'], $password_user);

                    if($email_user === $_POST['email'] && $password_verification && $token == null) {
                        header("Location: dashboard");
                        session_start ();
                        $_SESSION['email'] = $_POST['email'];
                        $_SESSION['password'] = $_POST['password'];
                    }elseif(!$password_verification){
                        $result[] = "Votre mot de passe est incorrect";
                        $view->assign('result', $result);
                    }elseif($token !== null) {
                        $result[] = "Veuillez activer votre compte";
                        $view->assign('result', $result);
                    }
                }else{}
            }
        }

        $view->assign("config",$config);
    }

    public function register()
    {
        $user = new UserModel();

        $view = new View("Register");
        $view->assign("user", $user);

        if(!empty($_POST)) {

            $errors = Verificator::checkForm($user->getRegisterForm(), $_POST);

            if(empty($errors)) {
                $firstname = addslashes(htmlspecialchars($_POST['firstname']));
                $lastname = addslashes(htmlspecialchars($_POST['lastname']));
                $email = addslashes($_POST['email']);
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $passwordConfirmation = password_hash($_POST['passwordConfirm'], PASSWORD_DEFAULT);

                $userEmail = $user->uniqueMailVerification($email);

                if($userEmail[0] != 0){
                    echo "Votre email existe déjà !";
                }
                elseif(password_verify($_POST['password'], $password) !== password_verify($_POST['passwordConfirm'], $passwordConfirmation)) {
                    echo "Vos mots de passe ne correspondent pas !";
                }
                else {
                    $user->setFirstname($firstname);
                    $user->setLastname($lastname);
                    $user->setEmail($email);
                    $user->setPassword($password);
                    $user->generateToken();

                    $user->save();

                    $token = $user->getToken();

                    $mailConfirmation = new Mail();
                    $mailConfirmation->main(
                        $email, 
                        "Confirmation inscription FoodPressCMS", 
                        "
                        Bonjour " . $user->getFirstname() .
                        " <br><br>Nous avons bien reçu vos informations. <br>
                        Afin de valider votre compte merci de cliquer sur le lien suivant <a href='http://localhost:81/confirmAccount?token=".$token."'>Ici</a> <br><br>
                        Cordialement,<br>
                        <a href=''>L'Equipe de FoodPressCMS</a>
                        "
                    );

                    $_SESSION['flash']['success'] = "Un e-mail de confirmation vous a été envoyé pour valider votre compte !";
                    // header('Location: login');
                    exit();
                }
            }
        }
    }

    public function logout()
    {
        session_start ();

        session_unset ();

        session_destroy ();

        header('Location: login');
    }

    public function redirection(){
        $test = "it works !";
        $session = new Session();
        // header('Location: /forgetPassword');
    }

    public function forgetPassword()
    {
        if(isset($test)) {
            echo $test;
            die();
        }
        $user = new UserModel();
        $view = new View("forgetPassword");
        $view->assign("user", $user);
    }

    public function sendPasswordReset()
    {
        $user = new UserModel();
        if(!empty($_POST)) {
            $result = Verificator::checkForm($user->getForgetPasswordForm(), $_POST);
            if(empty($result)) {
                $user = $user->getOneBy(["email" => $_POST['email']]);
                if(!empty($user)) {
                    // echo "<pre>";
                    // var_dump($user);
                    // echo "</pre>";
                    $user = $user[0];
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
                    if($mail->Send()) {
                        echo "Email envoyé";
                        $passwordReset->save();
                    }else{
                        echo "Une erreur ";
                    }
                    $mail->smtpClose();
                }else{
                    echo "l'email n'existe pas";
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
        if(!empty($passwordReset->getOneBy(["token" => $_GET["token"]])[0])) {
            $passwordReset = $passwordReset->getOneBy(["token" => $_GET["token"]])[0];
            if($passwordReset->tokenexpiry > time()) {
                $session = new Session();
                $session->set("token", $passwordReset->getToken());
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
        $passwordReset = new PasswordReset();
        $session = new Session();
        $passwordReset = $passwordReset->getOneBy(["token" => $session->get('token')])[0];
        if(!empty($passwordReset) && $passwordReset->tokenexpiry > time()) {
            if(!empty($_POST)) {
                $result = Verificator::checkForm($user->getChangePasswordForm(), $_POST);
                if(empty($result)) {
                    $user = $user->setId($passwordReset->getUserId());
                    $user->setPassword($_POST['password']);
                    $user->save();
                    echo "Mot de passe changé";
                }
            }
        }
    }

    public function confirmAccount(){
        $user = new UserModel();

        $view = new View("confirmAccount");

        $token = $_GET['token'];

        $userInfo = $user->getOneBy(['token' => $token]);
        $object = $userInfo[0];

        $userToken = $object->token;
        $userId = $object->id;

        if($token === $userToken){
            $user->activateAccount($userId);
        }
        else {
            echo "Token non valide !";
        }
    }
}
