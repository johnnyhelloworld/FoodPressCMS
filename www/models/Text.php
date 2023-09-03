<?php

namespace App\models;

use App\core\Sql;

class Text extends Sql
{
    protected $id;
    protected $content;
    protected $fp_block_id;

    public function __construct()
    {
        parent::__construct();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent($content): void
    {
        $this->content = $content;
    }

    public function getBlockId(): ?int
    {
        return $this->fp_block_id;
    }

    public function setBlockId($fp_block_id): void
    {
        $this->fp_block_id = $fp_block_id;
    }
}