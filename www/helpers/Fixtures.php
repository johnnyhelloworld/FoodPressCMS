<?php

namespace App\Helpers;

use App\core\Sql;
use App\core\Router;

use App\Helpers\Slugger;

use App\models\Page as PageModel;
use App\models\Theme as ThemeModel;
use App\models\Like as LikeModel;
use App\models\MenuItem as MenuItemsModel;
use App\models\Comment as CommentModel;
use App\models\Category;
use App\models\Recipe as RecipeModel;
use App\models\User;
use App\models\Block as BlockModel;
use App\models\Form;
use App\models\Input;
use App\models\Connexion;
use App\models\Contact;
use App\models\Report as ReportModel;
use App\models\Text;

class Fixtures extends Sql
{
	private function writeRoute(array $params): void
	{
		$content = file_get_contents('routes.yml');
		$content .= "\n\n" . strtolower($params['route']) . ':';
		$content .= "\n  controller: " . $params['model'];
		$content .= "\n  action: " . $params['action'];
		$content .= "\n  role: [" . $params['role'] . "]";
		file_put_contents('routes.yml', $content);
	}


	public function generateFixtures()
	{
        $theme = new ThemeModel();
        $theme->truncate('theme');
        $theme->setName('Thème FoodPress');
        $theme->setDescription("Un thème culinaire à la FoodPress");
        $theme->setDomain('https://foodpresscms.fr');
        $theme->setImage('FoodPress.png');
        $theme->save();

		$categoryManager = new Category();
		$categoryManager->truncate('category');
		$categoryNames = ['Végétarien' => 'ratatouille.jpg', 'Viande' => 'lapin-moutarde.png', 'Pâte' => 'carbonara.jpg', 'Asie' => 'boeuf-caramel.jpg'];
		foreach ($categoryNames as $key => $value) {
			$category = new Category();
			$category->setName($key);
			$category->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum');
			$category->setImage($value);
			$category->setSlug(Slugger::sluggify(strtolower($key)));
			$category->save();

			$params['route'] = Slugger::sluggify($key);
			$params['role'] = 'public' ?? null;
			$params['model'] = 'categories' ?? null;
			$params['action'] = 'categoryPage';
			$this->writeRoute($params);
		}

		$recipeManager = new RecipeModel();
		$recipeManager->truncate('recipe');
		$categories = $categoryManager->getAll();
		$content = "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat facere possimus, omnis voluptas assumenda est, omnis dolor repellendus. Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet ut et voluptates repudiandae sint et molestiae non recusandae. Itaque earum rerum hic tenetur a sapiente delectus, ut aut reiciendis voluptatibus maiores alias consequatur aut perferendis doloribus asperiores repellat.";
		$content = str_replace(',', '', $content);
		$content = str_replace('.', '', $content);
		$arrayContent =  explode(' ', $content);

		for ($i = 0; $i < count($categories); $i++) {
			for ($j = 0; $j < 20; $j++) {
			$title = $arrayContent[rand(0, count($arrayContent) - 1)] . ' ' . $arrayContent[rand(0, count($arrayContent) - 1)] . ' ' . $arrayContent[rand(0, count($arrayContent) - 1)];
			$recipe = new RecipeModel();
			$recipe->setCategoryId($categories[$i]['id']);
			$recipe->setTitle($title);
			$recipe->setContent($content);
            $recipe->setDateCreated((new \DateTime('now'))->format('Y-m-d H:i:s'));
            $recipe->setDateUpdated((new \DateTime('now'))->format('Y-m-d H:i:s'));
			$recipe->setSlug(Slugger::sluggify($title));
			$recipe->save();
			}
		}

		$userManager = new User();
		$userManager->truncate('user');
		$usersArray = [
			'john' => 'doe', 'jane' => 'doe', 'ela' => 'fitzerald', 'bob' => 'mercier', 'yvan' => 'dupont', 'peter' => 'scwalk', 'piotr' => 'weber',
			'jacques' => 'Lousier', 'Pierre' => 'durand', 'olga' => 'zwetlik', 'mamadou' => 'mbala', 'eva' => 'garnier', 'cecile' => 'lamy', 'agathe' => 'domy',
			'sylvie' => 'bellanger', 'samir' => 'el boustani', 'louis' => 'costas', 'elmut' => 'kholer', 'malik' => 'bensala', 'cerise' => 'dupont',
			'denis' => 'grognier', 'nicolas' => 'dupont', 'ingrid' => 'marnier', 'estelle' => 'grosjean', 'patricia' => 'mernier', 'jean' => 'lebon', 'priscilla' => 'wallace'
		];
		foreach ($usersArray as $key => $value) {
			$user = new User();
			$user->setFirstName(ucfirst(strtolower($key)));
			$user->setLastname(ucfirst(strtolower($value)));
			$user->setEmail(strtolower($key) . strtolower($value) . '@gmail.com');
			$user->setStatus(1);
			$user->setPassword(password_hash('1234', PASSWORD_BCRYPT));
			$user->setRole('user');
			$user->save();
		}
		$admin = new User();
		$admin->setFirstName('Johnny');
		$admin->setLastname('Chen');
		$admin->setEmail('johnny.chen@hotmail.fr');
		$admin->setStatus(1);
		$admin->setPassword(password_hash('1234', PASSWORD_BCRYPT));
		$admin->setRole('admin');
		$admin->save();


		$recipes = $recipeManager->getAll();
		$users = $userManager->getAll();
		$commentManager = new CommentModel();
		$likeManager = new LikeModel();
		$commentManager->truncate('comment');
		$likeManager->truncate('like');
		$contentComment = "Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatu unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab ";
		$contentComment = str_replace(',', '', $contentComment);
		$contentComment = str_replace('.', '', $contentComment);
		$explode = explode(' ', $contentComment);

		foreach ($recipes as $recipe) {
			$rand = rand(2, 10);
			$rand2 = rand(1, 3);

			for ($j = 0; $j < $rand; $j++) {
                $comment = new CommentModel();
                $comment->setParentId(null);
                $comment->setAuthorId($users[rand(0, count($users) - 1)]['id']);
                $comment->setRecipeId($recipe['id']);
                $comment->setTitle($explode[rand(1, count($explode) - 1)] . ' ' . $explode[rand(1, count($explode) - 1)]);
                $comment->setContent(substr($contentComment, rand(0, 50), rand(100, strlen($contentComment))));
                $comment->setDateCreated((new \DateTime('now'))->format('Y-m-d H:i:s'));
                $comment->save();
			}
			for ($k = 0; $k < $rand2; $k++) {
                $like = new LikeModel();
                $like->setUserId($users[rand(0, count($users) - 1)]['id']);
                $like->setRecipeId($recipe['id']);
                $like->save();
			}
		}

		$comments = $commentManager->getAll();
		foreach ($comments as $comment) {
			$rand2 = rand(1, 3);
			for ($j = 0; $j < $rand2; $j++) {
			$reply = new CommentModel();
			$reply->setParentId($comment['id']);
			$reply->setAuthorId($users[rand(0, count($users) - 1)]['id']);
			$reply->setRecipeId($comment['fp_recipe_id']);
			$reply->setTitle($explode[rand(1, count($explode) - 1)] . ' ' . $explode[rand(1, count($explode) - 1)]);
			$reply->setContent(substr($contentComment, rand(0, 50), rand(100, strlen($contentComment))));
            $reply->setDateCreated((new \DateTime('now'))->format('Y-m-d H:i:s'));
			$reply->save();
			}
		}

		// pages de base constituant le thème -> A définir 
		$arrayPages = ['Home' => 'home', 'Recipes' => 'recipes', 'Presentation' => 'presentation', 'Contact' => 'contact', 'About' => 'about'];
		$itemManager = new MenuItemsModel();
		$pageManager = new PageModel();
		$pageManager->truncate('page');
		$itemManager->truncate('menuitem');

		$position = 1;
		foreach ($arrayPages as $key => $value) {

			$page = new PageModel();
			$page->setTitle($key);
			$page->setType($value);
			$page->setLink('/' . $value);
			$page->setThemeId(1);
			$page->save();

			$item = new MenuItemsModel();
			$item->setLink('/' . $value);
			$item->setName($key);
			$item->setPosition($position);
			$item->save();

			$position++;
		}
	}
}