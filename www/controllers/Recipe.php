<?php

namespace App\controllers;

use App\core\View;
use App\models\Recipe as RecipeModel;
use App\core\verificator\VerificatorRecipe;
use App\models\Category as CategoryModel;
use App\core\Sql;
use App\core\Session;


class Recipe extends Sql{

	public function recipeCreate() 
    {
		$view = new View("recipe");
		$recipe = new RecipeModel();
		$view->assign("recipe", $recipe);

		// $category = new CategoryModel();
		// echo '<pre>';
		// print_r($category->getAll());
		// echo '</pre>';
		// die();

		if($_SERVER["REQUEST_METHOD"] == "POST"){
			//var_dump($_POST);
			//die();
			$title = addslashes(htmlspecialchars($_POST['title']));
			$content = addslashes(htmlspecialchars($_POST['content']));
			$category_id = $_POST['category_id'];

			$result = VerificatorRecipe::validate($recipe->getRecipeForm(), $_POST);

			if(count($result) > 0) {
				$view->assign('result', $result);
				return;
			}

			$recipe->setTitle($title);
			$recipe->setContent($content);
			$recipe->setCategoryId($category_id);
			// $recipe->setPosition($_POST['position']);
			$recipe->save();

			$recipeData = [
				'id' => $recipe->getId(),
			];

			$recipeId = $recipe->getOneBy($recipeData);

			$object = $recipeId[0];
			$id = $object->id;

			header('Location: /allRecipe');
		}
	}
}