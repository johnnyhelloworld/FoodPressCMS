<?php

namespace App\models;


use App\core\Sql;

class Recipe extends Sql
{
    protected $id;
    protected $title;
    protected $content;
    protected $position;
    protected $block_id;
    protected $category_id;

    public function __construct()
    {
        parent::__construct();
    }

    public function getId():?int
    {
        return $this->id;
    }

    public function getTitle():?string
    {
        return $this->title;
    }

    public function setTitle($title):void
    {
        $this->title = $title;
    }	

    public function getContent():?string
    {
        return $this->content;
    }

    public function setContent($content):void
    {
        $this->content = $content;
    }

    public function getPosition():?int
    {
        return $this->position;
    }

    public function setPosition($position):void
    {
        $this->position = $position;
    }

    public function getBlockId():?int
    {
        return $this->block_id;
    }

    public function setBlockId($block_id):void
    {
        $this->block_id = $block_id;
    }

    public function getCategoryId():?int
    {
        return $this->category_id;
    }

    public function setCategoryId($category_id):void
    {
        $this->category_id = $category_id;
    }

    public function getRecipeForm($params = null):array
    {
        $category = new Category();
        $categories = $category->getAll();

        $datas = [];
        for ($i = 0; $i < count($categories); $i++) {
            $datas[] = [$categories[$i]['id'], $categories[$i]['name']];
        }

        return [
            "config" => [
                "method" => "POST",
                "action" => "/recipe",
                "class" => "formRecipe",
                "id" => "formRecipe",
                "submit" => "Enregistrer",
            ],

            "inputs" => [
                "title" => [
                    "value" => $params != null ? $params['value'] : "", // permet de préremplir le formulaire lors de la modification du formulaire
                    "type" => "text",
                    "id" => "title",
                    "class" => "title",
                    "placeholder" => "Titre de la recette",
                    //"required" => "required",
                    "error" => "Veuillez entrer un titre",
                ],

                "content" => [
                    "type" => "textarea",
                    "id" => "content",
                    "class" => "content",
                    "placeholder" => "Contenu de la recette",
                    //"required" => "required",
                    "min" => "10",
                    "max" => "1000",
                    "error" => "Veuillez entrer un contenu",
                ],

                "category_id" => [
                    "type" => "select",
                    "class" => "categories",
                    //"required" => "required",
                    "value" => $datas,
                ],
            ]
        ];
    }
}