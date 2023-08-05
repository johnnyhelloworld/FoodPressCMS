<?php

namespace App\models;

use App\core\Sql;

class PasswordReset extends Sql
{
    /** 
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $token;

    /**
     * @var string
     */
    protected $tokenExpiry;

    /** 
     * @var int
     */
    protected $fp_user_id;

    public function __construct(){
        parent::__construct();
    }

    public function getId():?int
    {
        return $this->id;
    }
    
    public function getToken():?string
    {
        return $this->token;
    }

    public function generateToken():void
    {
        $length = 32;
        $this->token = bin2hex(random_bytes($length));
    }
    
    public function getTokenExpiry():?string
    {
        return $this->tokenExpiry;
    }

    public function generateTokenExpiry():void
    {
        $this->tokenExpiry = time() + (60*15);
    }
    
    public function getUserId():?int
    {
        return $this->fp_user_id;
    }

    public function setUserId(?user $user):void
    {
        $this->fp_user_id = $user->getId();
    }

    public function save(): void
    {
        parent::save();
    }

    public function getResetPasswordForm(): array
    {
        return [
            "config"=>[
                "method"=>"POST",
                "action"=>"",
                "id"=>"formResetPassword",
                "class"=>"formResetPassword",
                "submit"=>"S'inscrire"
            ],
            "inputs"=>[
                "email"=>[
                    "placeholder"=>"Votre email ...",
                    "type"=>"email",
                    "id"=>"emailRegister",
                    "class"=>"formRegister",
                    "required"=>true,
                    "error"=>"Email incorrect",
                    "unicity"=>true,
                    "errorUnicity"=>"Un compte existe déjà avec cet email"
                ]
            ]
        ];
    }
}