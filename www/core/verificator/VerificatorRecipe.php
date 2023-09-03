<?php
    namespace App\Core\verificator;

    class VerificatorRecipe
    {
		public static function validate($config, array $data): array
		{
			if(count($config["inputs"]) != count($_POST) && count($config["inputs"]) != count($_GET)){
                die("Tentative de hack");
            }

			$errors = [];

			if(!$data['category_id'] || !intval($data['category_id']))
			$errors['categories'] =  'Catégorie non renseignée';

			if(!$data['title'])
			$errors['title'] =  'Titre non renseigné';

			if(strlen($data['title']) > 100)
			$errors['title'] =  'La taille maximum est de 100 caractères';

			if(!$data['content'])
			$errors['content'] =  'Veuillez renseigner un contenu';

			return $errors;
		}
    }