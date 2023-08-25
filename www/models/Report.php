<?php

namespace App\models;

use App\core\Sql;

class Report extends Sql
{
    protected $id;
    protected $message;
    protected $email;
    protected $has_read;
    protected $date_created;
    protected $fp_comment_id;

    public function __construct()
    {
        parent::__construct();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage($message): void
    {
        $this->message = $message;
    }

    public function getHasRead(): int
    {
        return $this->has_read;
    }

    public function setHasRead($has_read): void
    {
        $this->has_read = $has_read;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail($email): void
    {
        $this->email = $email;
    }

    public function getCreatedAt(): ?string
    {
        return $this->date_created;
    }

    public function setCreatedAt($date_created): void
    {
        $this->date_created = $date_created;
    }

    public function getCommentId(): ?int
    {
        return $this->fp_comment_id;
    }

    public function setCommentId($fp_comment_id): void
    {
        $this->fp_comment_id = $fp_comment_id;
    }


    public function getReportForm($params = null): array
    {
        return [
            "config" => [
                "method" => "POST",
                "action" => "",
                "class" => "formReport",
                "id" => "formReport",
                "submit" => "valider",
            ],

            "inputs" => [
                "email" => [
                    "value" =>  "",
                    "type" => "text",
                    "id" => "emailReport",
                    "class" => "emailReport",
                    "placeholder" => "Entrer votre email",
                    //"required" => "required",
                    "error" => "Veuillez entrer un email valide",
                ],
                "message" => [
                    "value" =>  "",
                    "type" => "textarea",
                    "id" => "messageReport",
                    "class" => "messageReport",
                    "placeholder" => "Votre message",
                    // "required" => "required",
                    "min" => "10",
                    "max" => "3000",
                    "error" => "Veuillez entrer un message",
                ]
            ]
        ];
    }
}