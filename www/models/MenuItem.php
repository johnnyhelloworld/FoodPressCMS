<?php

namespace App\models;


use App\core\Sql;

class MenuItem extends Sql
{
    protected $id;
    protected $name;
    protected $link;
    protected $js_class;
    protected $js_id;
    protected $position;

    public function __construct()
    {
        parent::__construct();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName($name): void
    {
        $this->name = $name;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink($link): void
    {
        $this->link = $link;
    }

    public function getJsClass(): ?string
    {
        return $this->js_class;
    }

    public function setJsClass($js_class): void
    {
        $this->js_class = $js_class;
    }

    public function getJsId(): ?string
    {
        return $this->js_id;
    }

    public function setJsId($js_id): void
    {
        $this->js_id = $js_id;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition($position): void
    {
        $this->position = $position;
    }
}