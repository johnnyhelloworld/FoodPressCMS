<?php

namespace App\controllers;

use App\core\Sql;
use App\core\Router;

use App\helpers\Fixtures;
use App\helpers\Slugger;

use App\models\Page as PageModel;
use App\models\User as UserModel;
use App\models\Recipe as RecipeModel;
use App\models\Report as ReportModel;
use App\models\MenuItem as MenuItemsModel;
use App\models\Block as BlockModel;
use App\models\Theme as ThemeModel;

class Admin extends Sql
{
    public function dashboard(): void
    {
        $report = new ReportModel();
        $reports = $report->getReportNotifications();
        $_SESSION['report'] = count($reports);

        Router::render('admin/home.php');
    }

    public function getUserProfile(): void
    {
        $user = new UserModel();
        Router::render('admin/user/userprofile.php', ["user" => $user]);
    }

    public function indexRecipe()
    {
        $recipe = new RecipeModel();

        $allRecipe = $recipe->getAll();

        Router::render("admin/recipe/recipes.php", [
            "allRecipe" => $allRecipe,
        ]);
    }

    public function editPage()
    {
        $pageModel = new PageModel();
        $block = new BlockModel();

        $data = $pageModel->getOneBy(['title' => $_GET['page']]);
        $page = $data[0];

        $blocksPage = $block->getBlockByPosition($page->getId());

        $positionBlocks = count($blocksPage);
        $position = $positionBlocks + 1;

        if (!$page) {
            header('Location: addpage');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $block->createBlock($position, 'untitled', $page->getId());
            header('Location: /editpage?page=' . $_GET['page']);
        }
        Router::render('admin/page/editpage.php', ['blocksPage' => $blocksPage]);
    }

    public function deletePageAdmin(): void
    {
        $page = new PageModel();
        $menuItem = new MenuItemsModel();
        $item = $menuItem->getOneBy(['link' => '/' . $_GET['page']])[0] ?? null;
        
        if ($item != null) {
            $item->delete($item->getId());
        }
            $page->deletePage($_GET['page']);
            $this->eraseRoute($_GET['page']);

        header('Location: addpage');
    }

    public function addPage(): void
    {
        $themeManager = new ThemeModel();
        $pageManager = new PageModel();
        $pages = $pageManager->getAll();

        $params = [];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            if (!$_POST['page_title'] || !$_POST['page_role'] || !$_POST['type']) {
                throw new \Exception('missing parameters');
            }
            $params['route'] = Slugger::sluggify(($_POST['page_title'])) ?? null;
            $params['role'] = strtolower($_POST['page_role']) ?? null;
            $params['model'] = strtolower($_POST['type']) ?? null;
            $params['action'] = 'index' .  ucfirst($params['model']) ?? null;

            $pageManager = new PageModel();

            $currentPages = $pageManager->getAll();

            foreach ($currentPages as $currentPage) {
                if ($currentPage['type'] == $params['model']) {
                    $message = 'Page déjà existante';
                    Router::render('admin/addpage.php', ['pages' => $pages, "message" => $message]);
                }
            }

            $pageManager->setTitle($params['route']);
            $pageManager->setType($params['model']);
            $pageManager->setLink('/' . Slugger::sluggify($params['route']));
            $pageManager->setThemeId(1); // remplacer par la suite par l'id_theme en SESSION
            $pageManager->save();

            $pageData = $pageManager->getOneBy(['title' => $pageManager->getTitle()]);
            $page = $pageData[0];

            $block = new BlockModel();
            $block->setPageId($page->getId());
            $block->setPosition(1); // create a default position
            $block->setTitle($_POST['page_title']);
            $block->save();

            $this->writeRoute($params);

            Router::render('admin/page/addpage.php');
        }
        Router::render('admin/page/addpage.php', ['pages' => $pages]);
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

        Router::render('admin/adminmember.php', ["users" => $users]);
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
    
    public function editMenu()
    {
        $menuItem = new MenuItemsModel();
        $items = $menuItem->getAllByPosition();

        $page = new PageModel();
        $pages = $page->getAll();

        Router::render('admin/editmenu.php', [
            'items' => $items,
            'pages' => $pages
        ]);
    }

    public function addItem()
    {
        $menuItem = new MenuItemsModel();
        $count = count($menuItem->getAllByPosition());
        $menuItem->setLink("/{$_POST['route']}");
        $menuItem->setName($_POST['name']);
        $menuItem->setPosition($count + 1);
        $menuItem->save();

        echo json_encode(['id' => $count + 1]);
    }

    public function moveItemPosition(): void
    {
        $block = new MenuItemsModel();

        foreach ($_POST as $key => $value) {
            $block->updateItemPosition($key, $value);
        }
        echo json_encode(['data' => $_POST, 'objet' => $block]);
    }
}