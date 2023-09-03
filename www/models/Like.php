<?php

namespace App\models;


use App\core\Sql;

class Like extends Sql
{
    protected $id;
    protected $fp_user_id;
    protected $fp_recipe_id;

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
        return $this->fp_user_id;
    }
    public function setUserId($fp_user_id): void
    {
        $this->fp_user_id = $fp_user_id;
    }

    public function getRecipeId(): ?int
    {
        return $this->fp_recipe_id;
    }
    public function setRecipeId($fp_recipe_id): void
    {
        $this->fp_recipe_id = $fp_recipe_id;
    }
}