<?php

namespace App\models;

use App\core\Sql;

class Form extends Sql
{
    protected $id;
    protected $title;
    protected $fp_block_id;

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

    public function getBlockId(): ?string
    {
        return $this->fp_block_id;
    }

    public function setBlockId($fp_block_id): void
    {
        $this->fp_block_id = $fp_block_id;
    }
}