<?php

namespace App\models;


use App\core\Sql;

class Like extends Sql
{
    protected $id;
    protected $user_id;
    protected $recipe_id;

    public function __construct()
    {
        parent::__construct();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }
    public function setUserId($user_id): void
    {
        $this->user_id = $user_id;
    }

    public function getRecipeId(): ?int
    {
        return $this->recipe_id;
    }
    public function setRecipeId($recipe_id): void
    {
        $this->recipe_id = $recipe_id;
    }
}