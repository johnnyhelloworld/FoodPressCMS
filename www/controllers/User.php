<?php

namespace App\controllers;

use App\core\User as Us;
use App\core\View;
use App\models\User as UserModel;
use App\core\Verificator;

class User 
{
    public function login()
    {
        $view = new View("login");
    }

    public function register()
    {
        $user = new UserModel();

        if(!empty($_POST)) {
            $result = Verificator::checkForm($user->getExamForm(), $_POST);
        }

        $view = new View("Register");
        $view->assign("user", $user);
    }

    public function logout()
    {
        die("logout");
    }

    public function forgetPassword(){
        $user = new UserModel();
        $user = $user->getBy(['email' => 'johnny.chen@hotmail.fr', 'id' => 1]);
        var_dump($user);
        die();
        $view = new View("forgetPassword");
        $view->assign("user", $user);
    }
}