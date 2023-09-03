<?php

namespace App\models;

use App\core\Sql;

class Input extends Sql
{
    protected $id;
    protected $placeholder;
    protected $name;
    protected $value;
    protected $label;
    protected $type;
    protected $js_id;
    protected $js_class;
    protected $fp_form_id;

    public function __construct()
    {
        parent::__construct();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlaceholder(): ?string
    {
        return $this->placeholder;
    }

    public function setPlaceholder($placeholder): void
    {
        $this->placeholder = $placeholder;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName($name): void
    {
        $this->name = $name;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue($value): void
    {
        $this->value = $value;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel($label): void
    {
        $this->label = $label;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType($type): void
    {
        $this->type = $type;
    }

    public function getJsclass(): ?string
    {
        return $this->js_class;
    }

    public function setJsclass($js_class): void
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

    public function getFormId(): ?string
    {
        return $this->fp_form_id;
    }

    public function setFormId($fp_form_id): void
    {
        $this->fp_form_id = $fp_form_id;
    }
}