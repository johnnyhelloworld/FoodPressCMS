<?php

namespace App\controllers;

use App\core\Router;
use App\core\Session;
use App\core\Mail;
use App\core\verificator\Verificator;

use App\models\User as UserModel;
use App\models\PasswordReset;

class User 
{
    public function login()
    {
        $user = new UserModel();

        if($_SERVER['REQUEST_METHOD'] == 'POST'){

            $errors = Verificator::checkForm($user->getLoginForm(), $_POST);
            if(count($errors) > 0){
                Router::render('front/security/login.php',["user" => $user, 'errors' => $errors]);
                return;
            }
            if(!isset($user->getOneBy(['email' => $_POST['email']])[0])){
                $errors = [];
                $errors[] = "Les informations sont incorrectes !";
                Router::render('front/security/login.php',["user" => $user, 'errors' => $errors]);
                return;
            }
            $user = $user->getOneBy(['email' => $_POST['email']])[0];

            if(!password_verify($_POST['password'], $user->getPassword())){
                Router::render('front/security/login.php',["user" => $user,'errors' => $errors]);
                return;
            }
            $status = $user->getStatus();

            if($status == 0){
                $errors = [];
                $errors[] = "Veuillez activer votre compte via l'email que vous avez reçu !";
                Router::render('front/security/login.php',["user" => $user, 'errors' => $errors]);
                return;
            }

            $_SESSION['email'] = $_POST['email'];
            $_SESSION['firstname'] = $_POST['firstname'];
            $_SESSION['lastname'] = $_POST['lastname'];
            $_SESSION['id'] = $_POST['id'];
            $_SESSION['role'] = $user->getRole();

            //Si user, on redirige vers home
            if ($_SESSION['role'] == 'user') {
                header("Location: default");
            }
            //Tant que le statut != 2, on redirige vers installation
            if ($_SESSION['role'] == 'admin' && $user->getStatus() == 1) {
                header("Location: installation");
            }

            if ($_SESSION['role'] == 'admin' && $user->getStatus() == 2) {
                header("Location: dashboard");
            }
        }
        Router::render('front/security/login.php',["user" => $user]);
    }

    // public function register()
    // {
    //     $user = new UserModel();

    //     Router::render('front/security/register.php', ['user' => $user]);

    //     if(empty($_POST)){
    //         die();
    //     }

    //     $errors = Verificator::checkForm($user->getRegisterForm(), $_POST);

    //     if(!empty($errors)){
    //         Router::render('front/security/register.php', ["errors" => $errors]);
    //         die();
    //     }
    //     $firstname = strip_tags($_POST['firstname']);
    //     $lastname = strip_tags($_POST['lastname']);

    //     if(isset($user->getOneBy(['email' => $_POST['email']])[0])){
    //         Router::render('front/security/register.php', ["errors" =>  ["L'utilisateur existe déjà"]]);
    //         die();
    //     }

    //     if($_POST['password'] !== $_POST['passwordConfirm']) {
    //         echo "Vos mots de passe ne correspondent pas !";
    //         die();
    //     }

    //     $user->setFirstname($firstname);
    //     $user->setLastname($lastname);
    //     $user->setEmail($_POST['email']);
    //     $user->setPassword($_POST['password']);
    //     $user->generateToken();
    //     $user->setRole('User');

    //     $user->save();

    //     $mail = new Mail();
    //     $mail->sendTo($_POST['email']);
    //     $mail->subject("Confirmation inscription FoodPressCMS");
    //     $mail->message("
    //     Bonjour " . $user->getFirstname() .
    //     " <br><br>Nous avons bien reçu vos informations. <br>
    //     Afin de valider votre compte merci de cliquer sur le lien suivant <a href='http://localhost:81/confirmAccount?token=".$user->getToken()."'>Ici</a> <br><br>
    //     Cordialement,<br>
    //     <a href=''>L'Equipe de FoodPressCMS</a>
    //     ");
    //     if(!$mail->send()){
    //         die("Vous rencontrer une erreur lors de l'envoie de mail");
    //     }
    //     Router::render('front/security/register.php', ["success" => "Un e-mail de confirmation vous a été envoyé pour valider votre compte !"]);
    // }

    public function register ()
    {
        $user = new UserModel();

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $errors = Verificator::checkForm($user->getRegisterForm(), $_POST);

            if(count($errors) > 0){
                Router::render('front/security/register.php',["user" => $user, "errors" => $errors]);

            }

            $firstname = strip_tags($_POST['firstname']);
            $lastname = strip_tags($_POST['lastname']);
            $email = strip_tags($_POST['email']);



            if(isset($user->getOneBy(['email' => $_POST['email']])[0])){
                $errors = [];
                $errors[] = "L'utilisateur existe déjà"; 
                Router::render('front/security/register.php', ["user" => $user, "errors" => $errors]);
            }

            $password = strip_tags($_POST['password']);
            $passwordConfirm = strip_tags($_POST['passwordConfirm']);

            if ($password !== $passwordConfirm) {
                $errors[] = "Les mots de passe ne correspondent pas";
                Router::render('front/security/register.php', ["user" => $user, "errors" => $errors]);
            }

            $user->setFirstname($firstname);
            $user->setLastname($lastname);
            $user->setEmail($email);
            $user->setPassword($password);
            $user->generateToken();
            $user->setDateUpdated((new \DateTime('now'))->format('Y-m-d H:i:s'));


            //Public
            if ($_SERVER['REQUEST_URI'] == '/register') {

            $user->setRole('user');

            $user->save();

            $mailBody = "Bonjour " . $user->getFirstname() .
            " <br><br>Nous avons bien reçu vos informations. <br>
            Afin de valider votre compte, merci de cliquer sur le lien suivant: <a href='http://localhost:81/confirmAccount?token=".$user->getToken()."'>Ici</a> <br><br>
            Cordialement,<br>
            <a href=''>L'Equipe de FoodPressCMS</a>";
            }
            
            //Admin
            if ($_SERVER['REQUEST_URI'] == '/adminregister') {

            $user->setRole('admin');

            $user->save();

            $mailBody = "Bonjour " . $user->getFirstname() .
            " <br><br>Nous avons bien reçu vos informations. <br>
            Afin de valider votre compte administrateur, merci de cliquer sur le lien suivant: <a href='http://localhost:81/confirmAccount?token=".$user->getToken()."'>Ici</a> <br><br>
            Cordialement,<br>
            <a href=''>L'Equipe de FoodPressCMS</a>";
            }

            // send email
            $mail = new Mail();
            $mail->sendTo($_POST['email']);
            $mail->subject("Confirmation inscription FoodPressCMS");
            $mail->message($mailBody);

            if (!$mail->send()) {
                die("Vous rencontrez une erreur lors de l'envoie de mail");
            }

            $_SESSION['success'] = "Un e-mail de confirmation vous a été envoyé pour valider votre compte !";
            header('Location:' . $_SERVER['REQUEST_URI']);

            }
            Router::render('front/security/register.php', ["user" => $user]);
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
        Router::render('front/security/forgetpassword.php', ["user" => $user]);
    }

    public function sendPasswordReset()
    {
        $user = new UserModel();
        Router::render('front/security/forgetpassword.php', ["user" => $user]);
            if(empty($_POST)){
                Router::render('front/security/forgetpassword.php', ["error" => "Un champ a disparu. =,("]);
            die();
        }
        $result = Verificator::checkForm($user->getForgetPasswordForm(), $_POST);
        if(!empty($result)){
            Router::render('front/security/forgetpassword.php', ["error" => "Aie ton email est mal écrit. =,("]);
            die();
        }
        $user = $user->getOneBy(["email" => $_POST['email']]);
        if(empty($user)){
            Router::render('front/security/forgetpassword.php', ["error" => "L'email n'existe pas. =,("]);
            die();
        }
        $user = $user[0]; 
        $passwordReset = new PasswordReset();
        $passwordReset->generateToken();
        $passwordReset->generateTokenExpiry();
        $passwordReset->setUserId($user);

        $mail = new Mail();
        $mail->sendTo($_POST['email']);
        $mail->subject("Changement de mot de passe");
        $mail->message('<h1 style="color:blue">FoodPressCMS</h1>
        <p>
            Nous avons bien reçus votre demande de changement de mot de passe.
        </p>
        <div>
            Changez de mot de passe en cliquant <a href="http://localhost:81/changePassword?token=' . $passwordReset->getToken() . '">ici</a>
        </div>');
        if(!$mail->send()){
            die("Vous rencontrer une erreur lors de l'envoie de mail");
        }
        $passwordReset->save();
        echo "Vous allez recevoir un mail pour modifier votre mail";
    }

    // public function changePassword(){
    //     $passwordReset = new PasswordReset();
    //     $user = new UserModel();
    //     if(empty($passwordReset->getOneBy(["token" => $_GET["token"]])[0])){
    //         die('<p style="color:red;">Le token n\'existe pas</p>');
    //     }
    //     $passwordReset = $passwordReset->getOneBy(["token" => $_GET["token"]])[0];
    //     if($passwordReset->tokenexpiry < time()){
    //         die('<p style="color:red;">Le token n\'est plus valide</p>');
    //     }
    //     $session = new Session();
    //     $session->set("token", $passwordReset->getToken());
    //     Router::render('front/security/changepassword.php', ["user" => $user]);
    // }

    public function changePassword()
    {
        $user = new UserModel();
        Router::render('admin/user/userprofilepassword.php', ["user" => $user]);

        $user = $user->getOneBy(['email' => $_SESSION['email']])[0];
        $status = $user->getStatus();

        if(password_verify($_POST['oldPassword'], $user->getPassword()) && $status == 1){
            if ($_POST['password'] !== $_POST['oldPassword'] ){
                if($_POST['password'] === $_POST['passwordConfirm'] ) {
                    $user->setPassword($_POST['password']);
                    $user->save();
                    echo "Votre mot de passe a été modifié";
                }
                else{
                    echo "Vos mots de passe ne correspondent pas !!!";
                    die();
                }
            } else
            {
                echo "Le nouveau mot de passe ne doit pas être similaire à l'ancien";
            }
        }else
        {
            echo "L'ancien mot de passe n'est pas bon";
            die();
        }
    }

    public function confirmPasswordChangement(){
        $user = new UserModel();
        $passwordReset = new PasswordReset();
        $session = new Session();
        $passwordReset = $passwordReset->getOneBy(["token" => $session->get('token')])[0];
        if(empty($passwordReset) && $passwordReset->tokenexpiry < time()){
            die("Le token n'existe pas ou est expiré");
        }
        if(empty($_POST)){
            die("Attention, vous n'avez pas rempli les champs");
        }
        $result = Verificator::checkForm($user->getChangePasswordForm(), $_POST);
        if(!empty($result)){
            die("Des erreurs sont présentes dans le formulaire");
        }
        $user = $user->setId($passwordReset->getUserId());
        $user->setPassword($_POST['password']);
        $user->save();
        echo "Mot de passe changé";
    }

    public function confirmAccount(){
        $user = new UserModel();

        if(!isset($user->getOneBy(['token' => $_GET['token']])[0])){
            die();
        }

        $user = $user->getOneBy(['token' => $_GET['token']])[0];

        if($user->getStatus() == 0){
            $user->setStatus(1);
            $user->save();
        }

        header('Location: /login');
    }

    public function getUserProfile(){
        $user = new UserModel();
        if(isset($_SESSION['email'])){
            $user = $user->getOneBy(['email' => $_SESSION['email']])[0];
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $firstname = strip_tags($_POST['firstname']);
            $lastname = strip_tags($_POST['lastname']);

            $user->setFirstname($firstname);
            $user->setLastname($lastname);

            $user->save();

            $_SESSION['succes'] = "Nous avons enregistré vos données avec succès !";
            header('Location:' . $_SERVER['REQUEST_URI']);
        }

        Router::render('admin/user/userprofile.php', ["user" => $user]);
    }
}
