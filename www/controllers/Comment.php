<?php

namespace App\controllers;


use App\core\Sql;
use App\core\verificator\VerificatorReport;
use App\core\Router;

use App\models\Comment as CommentModel;
use App\models\User as UserModel;
use App\models\Report as ReportModel;

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
		$comment->setDateCreated((new \DateTime('now'))->format('Y-m-d H:i:s'));
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
				"date" => substr($comment->getDateCreated(), 0, 10),
				"hour" => substr($comment->getDateCreated(), -9, 18),

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
		$comment->setDateCreated((new \DateTime('now'))->format('Y-m-d H:i:s'));
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
				"date" => substr($comment->getDateCreated(), 0, 10),
				"hour" => substr($comment->getDateCreated(), -9, 18),
				"parent" => $comment->getParentId(),
			]
		]);
	}

    public function reportComment()
    {
        $reportManager = new ReportModel();
        $commentManager = new CommentModel();
        $userManager = new UserModel();

        $commentDatas = $commentManager->getOneBy(['id' => $_GET['id']]);
        $comment = $commentDatas[0];
        $userDatas = $userManager->getOneBy(['id' => $comment->getAuthorId()]);
        $user = $userDatas[0];

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = htmlspecialchars($_POST['email']);
            $message = htmlspecialchars($_POST['message']);

            $result = VerificatorReport::validate($reportManager->getReportForm(), $_POST);

            if ($result && count($result) > 0) {
                Router::render('admin/report/reports.php', [
                    'result' => $result,
                    "reportManager" => $reportManager,
                    "comment" => $comment,
                    "author" => $user
                ]);
                return;
            }

            $report = new ReportModel();
            $report->setCommentId($_GET['id']);
            $report->setEmail($email);
            $report->setMessage($message);
            $report->setHasRead(0);
            $report->setCreatedAt((new \Datetime('now'))->format('Y-m-d H:i:s'));
            $report->save();

            header('Location: /recipes');
        }

        Router::render('front/report/reports.php', [
            "reportManager" => $reportManager,
            "comment" => $comment,
            "author" => $user
        ]);
    }

    public function getReports()
    {
        $report = new ReportModel();
        $reports = $report->getBy(['has_read' => 0]);

        $_SESSION['report'] = 0;

        foreach ($reports as $report) {
            $report->setHasRead(1);
            $report->save();
        }


        Router::render('admin/report/reports.php', ['reports' => $reports]);
    }
}