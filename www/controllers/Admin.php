<?php

namespace App\controllers;

use App\core\View;
use App\models\User as UserModel;

class Admin
{
    public function home()
    {
        $firstname = 'Johnny';
        $view = new View("dashboard", "back");
        $view->assign(['firstname' => $firstname]);

        $user = new UserModel();

        $view->assign(["user" => $user]);
    }

    public function memberView()
    {
        $view = new View("adminMember", "back");
        $user = new UserModel();

        $users = $user->getAll();

        $view->assign(["users" => $users]);
    }

    public function deleteUser()
    {
        $user = new UserModel();
        $user->delete($_GET['id']);

        header('Location: /adminmember');
    }

    public function editUserRole()
    {
        $userManager = new UserModel();

        $userDatas = $userManager->getOneBy(['id' => $_POST['id'] ]);
        $selectedRole = $_POST['role'];
        $user = $userDatas[0];
        $user->setRole($selectedRole);
        $user->setDateUpdated((new \DateTime('now'))->format('Y-m-d H:i:s'));
        $user->save();

        header('Location: /adminmember');
    }
}