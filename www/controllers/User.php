<?php

namespace App\controllers;

use App\core\User as Us;
use App\core\View;
use App\models\User as UserModel;
use App\core\verificator\Verificator;
use App\models\PasswordReset;
use App\core\Session;
use App\core\Sql;
use App\core\Mail;

class User 
{
    public function login()
    {
        $view = new View("Login", "empty");
        $user = new UserModel();
        $view->assign("user", $user);
        
        if(empty($_POST)){
            die();
        }

        $errors = Verificator::checkForm($user->getLoginForm(), $_POST);
        if(!empty($errors)){
            $view->assign('errors', $errors);
            die();
        }
        if(!isset($user->getOneBy(['email' => $_POST['email']])[0])){
            $view->assign('errors', ["Votre email ou mot de passe est invalide"]);
            die();
        }
        $user = $user->getOneBy(['email' => $_POST['email']])[0];

        if(!password_verify($_POST['password'], $user->getPassword())){
            $view->assign('errors', ["Votre email ou mot de passe est invalide"]);
            die();
        }
        $status = $user->getStatus();

        if($status == false){
            $view->assign('errors', ["Votre compte n'est pas encore actif"]);
            die();
        }
        $session = new Session();
        $session->set('email', $_POST['email']);
        header("Location: dashboard");
    }

    public function register()
    {
        $user = new UserModel();

        $view = new View("Register", "empty");
        $view->assign("user", $user);

        if(empty($_POST)){
            die();
        }

        $errors = Verificator::checkForm($user->getRegisterForm(), $_POST);

        if(!empty($errors)){
            $view->assign("errors", $errors);
            die();
        }
        $firstname = strip_tags($_POST['firstname']);
        $lastname = strip_tags($_POST['lastname']);

        if(isset($user->getOneBy(['email' => $_POST['email']])[0])){
            $view->assign("errors",  ["L'utilisateur existe"]);
            die();
        }

        if($_POST['password'] !== $_POST['passwordConfirm']) {
            echo "Vos mots de passe ne correspondent pas !";
            die();
        }

        $user->setFirstname($firstname);
        $user->setLastname($lastname);
        $user->setEmail($_POST['email']);
        $user->setPassword($_POST['password']);
        $user->generateToken();
        $user->setRole('User');

        $user->save();

        $mail = new Mail();
        $mail->sendTo($_POST['email']);
        $mail->subject("Confirmation inscription FoodPressCMS");
        $mail->message("
        Bonjour " . $user->getFirstname() .
        " <br><br>Nous avons bien reçu vos informations. <br>
        Afin de valider votre compte merci de cliquer sur le lien suivant <a href='http://localhost:81/confirmAccount?token=".$user->getToken()."'>Ici</a> <br><br>
        Cordialement,<br>
        <a href=''>L'Equipe de FoodPressCMS</a>
        ");
        if(!$mail->send()){
            die("Vous rencontrer une erreur lors de l'envoie de mail");
        }
        $view->assign("success", "Un e-mail de confirmation vous a été envoyé pour valider votre compte !");
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
        $view = new View("forgetpassword");
        $user = new UserModel();
        $view->assign("user", $user);
            if(empty($_POST)){
            $view->assign("error", "Un champ a disparu. =,(");
            die();
        }
        $result = Verificator::checkForm($user->getForgetPasswordForm(), $_POST);
        if(!empty($result)){
            $view->assign("error", "Aie ton email est mal écrit. =,(");
            die();
        }
        $user = $user->getOneBy(["email" => $_POST['email']]);
        if(empty($user)){
            $view->assign("error", "L'email n'existe pas. =,(");
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

    public function changePassword(){
        $passwordReset = new PasswordReset();
        $user = new UserModel();
        if(empty($passwordReset->getOneBy(["token" => $_GET["token"]])[0])){
            die('<p style="color:red;">Le token n\'existe pas</p>');
        }
        $passwordReset = $passwordReset->getOneBy(["token" => $_GET["token"]])[0];
        if($passwordReset->tokenexpiry < time()){
            die('<p style="color:red;">Le token n\'est plus valide</p>');
        }
        $session = new Session();
        $session->set("token", $passwordReset->getToken());
        $view = new View("changePassword");
        $view->assign("user", $user);
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

    public function isAdmin(){
        $user = new UserModel();
        $foundAdmin = isset($_SESSION['email']) ? $user->getOneBy(['email' => $_SESSION['email']])[0] : null;

        if(!empty($foundAdmin)){
            die("Admin non trouvé");
        }

        if($foundAdmin->getRole() == "Admin"){
            return true;
        }
    }

    public function isChef(){
        $user = new UserModel();
        $foundChef = isset($_SESSION['email']) ? $user->getOneBy(['email' => $_SESSION['email']])[0] : null;

        if(empty($foundChef)){
            die("Chef non trouvé");
        }

        if($foundChef->getRole() == "Chef"){
            return true;
        }
    }

    public function isSubcriber(){
        $user = new UserModel();
        $foundSub = isset($_SESSION['email']) ? $user->getOneBy(['email' => $_SESSION['email']])[0] : null;

        if(empty($foundSub)){
            die("Subscriber non trouvé");
        }

        if($foundSub->getRole() == "Subscriber"){
            return true;
        }
    }

    public function isUser(){
        $user = new UserModel();
        $foundUser = isset($_SESSION['email']) ? $user->getOneBy(['email' => $_SESSION['email']])[0] : null;

        if(empty($foundUser)){
            die("User non trouvé");
        }

        if($foundUser->getRole() == "User"){
            return true;
        }
    }
}
