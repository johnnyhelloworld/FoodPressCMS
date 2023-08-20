<?php

namespace App\controllers;

use App\core\View;
use App\models\Recipe as RecipeModel;
use App\core\verificator\VerificatorRecipe;
use App\models\Category as CategoryModel;
use App\models\Block as BlockModel;
use App\models\User as UserModel;
use App\core\Sql;
use App\core\Session;


class Recipe extends Sql{

	public function recipeCreate() 
    {
		$view = new View("recipe");
		$recipe = new RecipeModel();

		if($_SERVER["REQUEST_METHOD"] == "POST"){

			$title = addslashes(htmlspecialchars($_POST['title']));
			$content = addslashes(htmlspecialchars($_POST['content']));
			$category_id = $_POST['category_id'];

			$result = VerificatorRecipe::validate($recipe->getRecipeForm(), $_POST);

			if($result && count($result) > 0) {
				$view->assign(['result' => $result, "recipe" => $recipe]);
				return;
			}

			$recipe->setTitle($title);
			$recipe->setContent($content);
			$recipe->setCategoryId($category_id);
			$recipe->setDateCreated((new \DateTime('now'))->format('Y-m-d H:i:s'));
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
		$recipe = new RecipeModel();
        $category = new CategoryModel();
		$view = new View("detailsRecipe");
		$recipe_id = $_GET['id'];

		$RecipeDatas = $recipe->getOneBy(['id' => $recipeId]);
		$recipe = $RecipeDatas[0];

        $categoryDatas = $category->getOneBy(['id' => $recipe->getCategoryId()]);
        $category = $categoryDatas[0];

        $view->assign(["recipe" => $recipe, "category" => $category]);
	}

	public function allRecipe() {
		$view = new View("recipes");
		$recipe = new RecipeModel();

		$allRecipe = $recipe->getAll();

		$view->assign(["allRecipe" => $allRecipe]);
	}

	public function updateRecipe() {
        $recipe = new RecipeModel();
        $category = new CategoryModel();

        $view = new View("updaterecipe");

        $recipeId = isset($_GET['id']);

        $recipeDatas = $recipe->getOneBy(['id' => $recipeId]);
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
            $recipeObject->setUpdatedAt((new \DateTime('now'))->format('Y-m-d'));
            $recipeObject->save();

            header('Location: /recipes');
        }
        $view->assign(["params" => $params, "recipe" => $recipe]);
	}

	public function deleteRecipe() {
		$recipe = new ArticleModel();
		$recipe->delete($_GET['id']);

		header('Location: /recipes');

	}
}