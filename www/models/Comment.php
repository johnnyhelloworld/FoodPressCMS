<?php

namespace App\models;

use PDO;
use App\core\Sql;

class Comment extends Sql
{
    protected $id;
    protected $parent_id = null;
    protected $author_id;
    protected $fp_recipe_id;
    protected $title;
    protected $content;
    protected $date_created;

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
        return $this->fp_recipe_id;
    }
    public function setRecipeId($fp_recipe_id): void
    {
        $this->fp_recipe_id = $fp_recipe_id;
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

    public function getDateCreated(): ?string
    {
        return $this->date_created;
    }

    public function setDateCreated($date_created): void
    {
        $this->date_created = $date_created;
    }
}