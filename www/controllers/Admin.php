<?php

namespace App\controllers;

use App\core\Sql;
use App\core\View;
use App\core\Router;

use App\Helpers\Fixtures;
use App\models\Page as PageModel;
use App\models\User as UserModel;
use App\models\Theme as ThemeModel;
use App\models\Recipe as RecipeModel;
use App\models\Report as ReportModel;

class Admin extends Sql
{
    public function dashboard(): void
    {
        // pour les test, session en dur
        // a mettre au login si role du user = 'admin
        $_SESSION['role'] = 'admin';

        $report = new ReportModel();
        $reports = $report->getReportNotifications();
        $_SESSION['report'] = count($reports);

        Router::render('dashboard.php');
    }

    public function indexRecipe()
    {
        $recipe = new RecipeModel();

        $allRecipe = $recipe->getAll();

        Router::render("admin/recipe/recipes.php", [
            "allRecipe" => $allRecipe,
        ]);
    }

    public function addPage(): void
    {
        $pageManager = new PageModel();
        $pages = $pageManager->getAll();

        $params = [];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            if (!$_POST['page_title'] || !$_POST['page_role'] || !$_POST['type']) {
                throw new \Exception('missing parameters');
            }

            $params['route'] = strtolower($_POST['page_title']) ?? null;
            $params['role'] = strtolower($_POST['page_role']) ?? null;
            $params['model'] = strtolower($_POST['type']) ?? null;
            $params['action'] = 'index' .  ucfirst($params['model']) ?? null;

            $pageManager = new PageModel();

            $currentPages = $pageManager->getAll();

            foreach ($currentPages as $currentPage) {
                if ($currentPage['type'] == $params['model']) {
                    $message = 'Page déjà existante';
                    Router::render('admin/addpage.php', ["message" => $message]);
                }
            }

            $pageManager->setTitle($params['route']);
            $pageManager->setType($params['model']);
            $pageManager->setLink('/' . $params['route']);
            $pageManager->setThemeId(1);
            $pageManager->save();

            $pageData = $pageManager->getOneBy(['title' => $pageManager->getTitle()]);
            $page = $pageData[0];

            $this->writeRoute($params);
        }
        Router::render('admin/addpage.php', ['pages' => $pages]);
    }

    public function deletePageAdmin(): void
    {
        $page = new PageModel();

        $page->deletePage($_GET['page']);

        $this->eraseRoute($_GET['page']);

        header('Location: /addpage');
    }

    public function editPage()
    {
        $pageModel = new PageModel();
        Router::render('admin/editPage.php'); //vue a rajouté

        $data = $pageModel->getOneBy(['title' => $_GET['page']]);
        $page = $data[0];
    }

    public function editMenu()
    {
        Router::render('admin/editMenu.php');
    }

    // écrit la route dans routes.yml
    private function writeRoute(array $params): void
    {
        $content = file_get_contents('routes.yml');
        $content .= "\n\n" . strtolower($params['route']) . ':';
        $content .= "\n  controller: " . $params['model'];
        $content .= "\n  action: " . $params['action'];
        $content .= "\n  role: [" . $params['role'] . "]";
        file_put_contents('routes.yml', $content);

        $file = fopen('views/' . strtolower($params['route']) . '.php', 'a');
        fwrite($file, '<h1>' . ucfirst($params['route']) . '</h1>');
        fclose($file);
    }

    private function eraseRoute(string $route): void
    {
        $content = file_get_contents('routes.yml');
        $arrayContent = explode('/', $content);

        $output = [];
        for ($i = 0; $i < count($arrayContent); $i++) {
            if (strstr($arrayContent[$i], $route) == false && $arrayContent[$i] != '') {
                $output[] = '/' . $arrayContent[$i];
            }
        }

        $content = file_get_contents('routes.yml');
        $content = '';
        for ($i = 0; $i < count($output); $i++) {
            $content .= $output[$i];
        }
        file_put_contents('routes.yml', $content);
    }

    public function memberView()
    {
        
        $user = new UserModel();

        $users = $user->getAll();

        Router::render('adminmember.php', ["users" => $users]);
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

    public function loadFixtures():void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $fixtures = new Fixtures();
            $fixtures->generateFixtures();
            $message = 'fixtures enregistrées';
            Router::render('admin/fixture.php', ['message', $message]);

        }

        Router::render('admin/fixture.php');
    }
}