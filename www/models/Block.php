<?php

namespace App\models;


use App\core\Sql;

class Block extends Sql
{
    protected $id;
    protected $title;
    protected $position;
    protected $fp_page_id;

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

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition($position): void
    {
        $this->position = $position;
    }

    public function getPageId(): ?int
    {
        return $this->fp_page_id;
    }

    public function setPageId($fp_page_id): void
    {
        $this->fp_page_id = $fp_page_id;
    }
}