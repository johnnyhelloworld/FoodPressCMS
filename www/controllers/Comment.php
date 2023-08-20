<?php

namespace App\controllers;


use App\core\Sql;
use PDOException;
use App\core\View;
use App\core\Session;
use App\models\Comment as CommentModel;
use App\models\User as UserModel;
use App\core\verificator\VerificatorComment;


class Comment extends Sql
{
	public function commentCreate()
	{
		if (empty($_POST['title']) || empty($_POST['content']) || empty($_POST['recipe'])) {
			echo json_encode(['response' => ['error' => 'Missing informations', 'status' => 400]]);
			return;
		}

		$comment = new CommentModel();
		$comment->setAuthorId(1);  //remplacer par l'id session user
		$comment->setParentId(null);
		$comment->setRecipeId($_POST['recipe']);
		$comment->setTitle($_POST['title']);
		$comment->setContent($_POST['content']);
		$comment->setContentCreated((new \DateTime('now'))->format('Y-m-d H:i:s'));
		$comment->save();

		$userManager = new userModel();
		$userDatas = $userManager->getOneBy(['id' => $comment->getAuthorId()]);
		$user = $userDatas[0];

		echo json_encode([
			"success" => 'New Resource created',
			"status" => 201,
			"comment" => [
				"id" => $comment->getId(),
				"title" => $comment->getTitle(),
				"content" => $comment->getContent(),
				"user" => $comment->getAuthorId(),
				"firstname" => $user->getFirstname(),
				"lastname" => $user->getLastname(),
				"recipe" => $comment->getRecipeId(),
				"date" => substr($comment->getContentCreated(), 0, 10),
				"hour" => substr($comment->getContentCreated(), -9, 18),

			]
		]);
	}

	public function replyComment()
	{
		if (empty($_POST['replyContent']) || empty($_POST['userId']) || empty($_POST['parentId']) || empty($_POST['recipeId'])) {
			echo json_encode(['response' => ['error' => 'Missing informations', 'status' => 400]]);
			return;
		}

		$comment = new CommentModel();
		$comment->setAuthorId(1);  //remplacer par l'id session user !!  et créer la SESSION !
		$comment->setParentId($_POST['parentId']);
		$comment->setRecipeId($_POST['recipeId']);
		$comment->setContent($_POST['replyContent']);
		$comment->setContentCreated((new \DateTime('now'))->format('Y-m-d H:i:s'));
		$comment->save();

		$userManager = new userModel();
		$userDatas = $userManager->getOneBy(['id' => $comment->getAuthorId()]);
		$user = $userDatas[0];

		echo json_encode([
			"success" => 'Votre commentaire est en ligne',
			"status" => 201,
			"comment" => [
				"id" => $comment->getId(),
				"content" => $comment->getContent(),
				"user" => $comment->getAuthorId(),
				"firstname" => $user->getFirstname(),
				"lastname" => $user->getLastname(),
				"recipe" => $comment->getRecipeId(),
				"date" => substr($comment->getContentCreated(), 0, 10),
				"hour" => substr($comment->getContentCreated(), -9, 18),
				"parent" => $comment->getParentId(),
			]
		]);
	}
}