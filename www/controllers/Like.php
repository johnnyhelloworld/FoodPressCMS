<?php

namespace App\controllers;


use App\core\Sql;
use PDOException;
use App\models\Like as LikeModel;


class Like extends Sql
{
    public function createLike()
    {
        if (empty($_POST['action']) || empty($_POST['recipe'])) {
            echo json_encode(['response' => ['error' => 'Missing informations', 'status' => 400]]);
        }

        $like = new LikeModel();
        // remplacer par l'id de SESSION user !!!!!
        try {
            $like->toggleLikes(1, $_POST['recipe']);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }

        $manager = new LikeModel();
        $total_likes = $manager->countAllLikesByRecipe($_POST['recipe']);
        echo json_encode([
            'count_likes' => $total_likes['likes']
        ]);
    }

    public function getLikes()
    {
        if (empty($_POST['recipe'])) {
            echo json_encode(['response' => ['error' => 'Missing informations', 'status' => 400]]);
        }
        $manager = new LikeModel();
        $recipeLikes = $manager->getUserLikeByRecipe(1, $_POST['recipe']);

        echo json_encode([
            'recipeLikes' => count($recipeLikes)
        ]);
    }
}