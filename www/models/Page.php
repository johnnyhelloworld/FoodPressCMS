<?php

namespace App\models;


use App\core\Sql;

class Page extends Sql
{
    protected $id;
    protected $title;
    protected $link;
    protected $type;
    protected $fp_theme_id;


    public function __construct()
    {
        parent::__construct();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle($title): void
    {
        $this->title = $title;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink($link): void
    {
        $this->link = $link;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType($type): void
    {
        $this->type = $type;
    }

    public function getThemeId(): ?int
    {
        return $this->fp_theme_id;
    }

    public function setThemeId($fp_theme_id): void
    {
        $this->fp_theme_id = $fp_theme_id;
    }
}