<?php

namespace App\controllers;

use App\core\View;

class Admin
{
    public function home()
    {
        $firstname = 'Johnny';
        $view = new View("dashboard", "back");
        $view->assign('firstname', $firstname);
    }
}