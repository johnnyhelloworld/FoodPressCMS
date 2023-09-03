<?php

namespace App\controllers;

use App\core\Router;

class Error
{
    public function errorNotFound()
    {
        return Router::render('admin/errors/404.php');
    }
}