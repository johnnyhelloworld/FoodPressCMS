<?php

namespace App\models; 

use App\core\Sql;


class Category extends Sql
{
    protected $id;
    protected $name;
    protected $description;
    protected $image;
    protected $slug;

    public function __construct()
    {
        parent::__construct();
    }

    public function getId():?int
    {
        return $this->id;
    }

    public function getName():?string
    {
        return $this->name;
    }

    public function setName($name):void
    {
        $this->name = $name;
    }

    public function getDescription():?string
    {
        return $this->description;
    }

    public function setDescription($description):void
    {
        $this->description = $description;
    }

    public function setImage($image): void
    {
        $this->image = $image;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setSlug($slug): void
    {
        $this->slug = $slug;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }
}