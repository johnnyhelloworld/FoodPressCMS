<?php

namespace App\controllers;

use App\core\View;
use App\models\Recipe as RecipeModel;
use App\core\verificator\VerificatorRecipe;
use App\models\Category as CategoryModel;
use App\models\Block as BlockModel;
use App\models\User as UserModel;
use App\models\Comment as CommentModel;
use App\models\Like as LikeModel;
use App\core\Sql;
use App\core\Session;
use App\helpers\Slugger;


class Recipe extends Sql{

	public function recipeCreate() 
    {
		$view = new View("recipe");
		$recipe = new RecipeModel();

		if($_SERVER["REQUEST_METHOD"] == "POST"){

			$title = addslashes(htmlspecialchars($_POST['title']));
			$content = $_POST['content'];
			$category_id = $_POST['category_id'];

			$result = VerificatorRecipe::validate($recipe->getRecipeForm(), $_POST);

			if($result && count($result) > 0) {
				$view->assign(['result' => $result, "recipe" => $recipe]);
				return;
			}

			$recipe->setTitle($title);
			$recipe->setSlug(Slugger::sluggify($_POST['title']));
			$recipe->setContent($content);
			$recipe->setCategoryId($category_id);
			$recipe->setDateCreated((new \DateTime('now'))->format('Y-m-d H:i:s'));
			$recipe->setDateUpdated((new \DateTime('now'))->format('Y-m-d H:i:s'));
			// $recipe->setPosition($_POST['position']);
			$recipe->save();

			$recipeData = [
				'id' => $recipe->getId(),
			];

			$recipeId = $recipe->getOneBy($recipeData);

			$object = $recipeId[0];
			$id = $object->id;

			header('Location: /recipes');
		}
        $view->assign(["recipe" => $recipe]);
	}

    public function detailsRecipe() {
		$recipeManager = new RecipeModel();
        $category = new CategoryModel();
		$commentRecipe= new CommentModel();
		$likeRecipe = new LikeModel();
		$view = new View("detailsRecipe", "empty");
		$recipeId = $_GET['slug'];

		$recipeDatas = $recipeManager->getOneBy(['slug' => $recipeId]);
		$recipe = $recipeDatas[0];

		$like = count($likeRecipe->getUserLikeByRecipe(1, $recipeId)); // remplacer par l'id user id de session 
		$total_likes = $likeRecipe->countAllLikesByRecipe($recipe->getId());



        $categoryDatas = $category->getOneBy(['id' => $recipe->getCategoryId()]);
        $category = $categoryDatas[0];

		$comments = $commentRecipe->getCommentsByRecipe($recipe->getId());

		$replies = $commentRecipe->getRepliesByComment($recipe->getId());
		$countComments = $commentRecipe->countComments($recipe->getId());

		if (count($comments) > 0) {
			$view->assign(['comments' => $comments]);
		}
		if (count($replies) > 0) {
			$view->assign(['replies' => $replies]);
		}

        $view->assign(["recipe" => $recipe,
		 "category" => $category,
		 "countComments" => $countComments,
		 "like" => $like,
		 "total_likes" => $total_likes['likes']
		]);
	}

	public function indexRecipe() {
		$view = new View("recipes");
		$recipe = new RecipeModel();

		$allRecipe = $recipe->getAll();

		$view->assign(["allRecipe" => $allRecipe]);
	}

	public function updateRecipe() {
        $recipe = new RecipeModel();
        $category = new CategoryModel();

        $view = new View("updaterecipe");

        $recipeId = $_GET['slug'];

        $recipeDatas = $recipe->getOneBy(['slug' => $recipeId]);
        $recipeObject = $recipeDatas[0];

        $categoryDatas = $category->getOneBy(['id' => $recipeObject->getCategoryId()]);
        $categoryObject = $categoryDatas[0];

        $params = [
            // "id" => $recipeObject->getId(),
            "title" => $recipeObject->getTitle(),
            "content" => $recipeObject->getContent(),
            "selectedValue" => $categoryObject->getId()
        ];

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $title = htmlspecialchars($_POST['title']);
            $content = $_POST['content'];
            $categoryId = intval($_POST['category_id']);

            $result = VerificatorRecipe::validate($recipe->getRecipeForm(), $_POST);

            if ($result != null && count($result) > 0) {
                $view->assign(['result' => $result, "recipe" => $recipe, 'params' => $params]);
                return;
            }

            $recipeObject->setTitle($title);
            $recipeObject->setContent($content);
            $recipeObject->setCategoryId($categoryId);
            $recipeObject->setDateUpdated((new \DateTime('now'))->format('Y-m-d'));
            $recipeObject->save();

            header('Location: /recipes');
        }
        $view->assign(["params" => $params, "recipe" => $recipe]);
	}

	public function deleteRecipe() {
		$recipe = new RecipeModel();
		// $comment = new CommentModel();

		// $comment->deleteComments($_GET['id']);
		$recipe->delete($_GET['id']);

		header('Location: /recipes');

	}
}