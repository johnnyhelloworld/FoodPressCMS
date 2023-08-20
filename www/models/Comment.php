<?php

namespace App\models;

use PDO;
use App\core\Sql;

class Comment extends Sql
{
    protected $id;
    protected $parent_id = null;
    protected $author_id;
    protected $recipe_id;
    protected $title;
    protected $content;
    protected $created_at;

    public function __construct()
    {
        parent::__construct();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getParentId(): ?int
    {
        return $this->parent_id;
    }

    public function setParentId($parent_id): void
    {
        $this->parent_id = strtolower($parent_id);
    }


    public function getAuthorId(): ?int
    {
        return $this->author_id;
    }
    public function setAuthorId($author_id): void
    {
        $this->author_id = $author_id;
    }


    public function getRecipeId(): ?int
    {
        return $this->recipe_id;
    }
    public function setRecipeId($recipe_id): void
    {
        $this->recipe_id = $recipe_id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle($title): void
    {
        $this->title = $title;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent($content): void
    {
        $this->content = $content;
    }

    public function getCreatedAt(): ?string
    {
        return $this->created_At;
    }

    public function setCreatedAt($created_At): void
    {
        $this->created_At = $created_At;
    }
}