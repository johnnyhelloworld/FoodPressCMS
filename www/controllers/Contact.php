<?php

namespace App\controllers;

use App\core\Sql;
use App\core\Router;
use App\models\Contact as ContactModel;

class Contact extends Sql
{
    public function indexContact()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $message = isset($_POST['message']) ? $_POST['message'] : null;

            if (null == $message) {
                $alert = array('error', 'Veuillez renseigner un contenu');
                return Router::render('front/contact/index.php', ['alert' => $alert]);
            }
            $contact = new ContactModel();
            $contact->setMessage($message);
            $contact->setEmail($_SESSION['email'] ?? 'test@gmail.com');
            $contact->setDateCreated((new \DateTime('now'))->format('Y-m-d H:i:s'));
            $contact->save();
            $alert = array('success', 'Message envoyé');

            Router::render('front/contact/index.php', ['alert' => $alert]);
            return;
        }
        Router::render('front/contact/index.php');
    }
}