<?php

namespace App\models;

use App\core\Sql;


class User extends Sql
{
    public $id;
    protected $firstname = null;
    protected $lastname = null;
    public $email;
    protected $status = 0;
    public $password;
    protected $date_created;
    protected $date_updated;
    public $token = null;
    protected $role;
    
    public function __construct(){
        parent::__construct();
        $this->setDateCreated(time());
        $this->setDateUpdated(time());
    }

    public function __toString(): string
    {
        return serialize($this);
    }
    
    public function getId():?int
    {
        return $this->id;
    }
    
    public function getFirstname():?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname):void
    {
        $this->firstname = ucwords(strtolower(trim($firstname)));
    }
    
    public function getLastname():?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname):void
    {
        $this->lastname = strtoupper(trim($lastname));
    }
    
    public function getEmail():?string
    {
        return $this->email;
    }

    public function setEmail(?string $email):void
    {
        $this->email = $email;
    }
    
    public function getStatus():int
    {
        return $this->status;
    }

    public function setStatus(int $status):void
    {
        $this->status = $status;
    }

    
    public function getPassword():string
    {
        return $this->password;
    }

    public function setPassword(string $password):void
    {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    public function getDateCreated(): ?string
    {
        return $this->date_created;
    }

    public function setDateCreated($date_created): void
    {
        $this->date_created = date("Y-m-d h:i:s", $date_created);
    }

    public function getDateUpdated(): ?string
    {
        return $this->date_updated;
    }

    public function setDateUpdated($date_updated): void
    {
        $this->date_updated = date("Y-m-d h:i:s", $date_updated);
    }
    
    public function getToken():?string
    {
        return $this->token;
    }

    public function generateToken():void
    {
        $bytes = random_bytes(128);
        $this->token = substr(str_shuffle(bin2hex($bytes)), 0, 255);
    }

    public function getRole():string
    {
        return $this->role;
    }

    public function setRole(string $role):void
    {
        $this->role = $role;
    }

    public function save(): void
    {
        parent::save();
    }
    
    public function getRegisterForm(): array
    {
        return [
            "config"=>[
                "method"=>"POST",
                "action"=>"/register",
                "id"=>"formRegister",
                "class"=>"formRegister",
                "submit"=>"S'inscrire"
            ],
            "inputs"=>[
                "firstname"=>[
                    "label"=>"Prénom",
                    "placeholder"=>"Votre prénom ...",
                    "type"=>"text",
                    "id"=>"firstnameRegister",
                    "class"=>"formRegister",
                    "min"=>2,
                    "max"=>25,
                    "error"=>" Votre prénom doit faire entre 2 et 25 caractères",
                ],
                "lastname"=>[
                    "label"=>"Nom",
                    "placeholder"=>"Votre nom ...",
                    "type"=>"text",
                    "id"=>"lastnameRegister",
                    "class"=>"formRegister",
                    "min"=>2,
                    "max"=>100,
                    "error"=>" Votre nom doit faire entre 2 et 100 caractères",
                ],
                "email"=>[
                    "label"=>"Email",
                    "placeholder"=>"Votre email ...",
                    "type"=>"email",
                    "id"=>"emailRegister",
                    "class"=>"formRegister",
                    "required"=>true,
                    "error"=>"Email incorrect",
                    "unicity"=>true,
                    "errorUnicity"=>"Un compte existe déjà avec cet email"
                ],
                "password"=>[
                    "label"=>"Mot de passe",
                    "placeholder"=>"Votre mot de passe ...",
                    "type"=>"password",
                    "id"=>"pwdRegister",
                    "class"=>"formRegister",
                    "required"=>true,
                    "error"=>"Votre mot de passe doit faire au min 8 caratères avec une majuscule et un chiffre"
                ],
                "passwordConfirm"=>[
                    "label"=>"Confirmation du mot de passe",
                    "placeholder"=>"Confirmation ...",
                    "type"=>"password",
                    "id"=>"pwdConfirmRegister",
                    "class"=>"formRegister",
                    "required"=>true,
                    "error"=>"Votre confirmation de mot de passe ne correspond pas",
                    "confirm"=>"password"
                ],
            ]
        ];
    }
    public function getLoginForm(): array
    {
        return [
            "config"=>[
                "method"=>"POST",
                "action"=>"/login",
                "id"=>"formLogin",
                "class"=>"formLogin",
                "submit"=>"Se connecter"
            ],
            "inputs"=>[
                "email"=>[
                    "placeholder"=>"Votre email ...",
                    "type"=>"email",
                    "id"=>"emailRegister",
                    "class"=>"formRegister",
                    "required"=>true,
                    "error"=>"Email invalide",
                ],
                "password"=>[
                    "placeholder"=>"Votre mot de passe ...",
                    "type"=>"password",
                    "id"=>"pwdRegister",
                    "class"=>"formRegister",
                    "required"=>true,
                    "error"=>"Mot de passe invalide"
                ]
            ]
        ];
    }
    public function getForgetPasswordForm()
    {
        return [
            "config"=>[
                "method"=>"POST",
                "action"=>"sendPasswordReset",
                "id"=>"formResetPassword",
                "class"=>"formResetPassword",
                "submit"=>"Récuperer mot de passe"
            ],
            "inputs"=>[
                "email"=>[
                    "placeholder"=>"Votre email ...",
                    "type"=>"email",
                    "id"=>"emailRegister",
                    "class"=>"formRegister",
                    "required"=>true
                ]
            ]
        ];
    }
    public function getChangePasswordForm()
    {
        return [
            "config"=>[
                "method"=>"POST",
                "action"=>"confirmChange",
                "id"=>"formChangePassword",
                "class"=>"formChangePassword",
                "submit"=>"Changer de mot de passe"
            ],
            "inputs"=>[
                "password"=>[
                    "placeholder"=>"Votre mot de passe ...",
                    "type"=>"password",
                    "id"=>"changePassword",
                    "class"=>"changePassword",
                    "required"=>true,
                    "error"=>"mot de passe incorrect"
                ],
                "confirmPassword"=>[
                    "placeholder"=>"Confirmez votre mot de passe ...",
                    "type"=>"password",
                    "id"=>"changePassword",
                    "class"=>"changePassword",
                    "required"=>true,
                    "error"=>"Pas le meme mot de passe"
                ]
            ]
        ];
    }
    // public function getLogoutButton(){
    //     return [
    //         "config"=>[
    //             "method"=>"POST",
    //             "action"=>"logout",
    //             "id"=>"logout_button",
    //             "class"=>"logout",
    //             "submit"=>"Se déconnecter"
    //         ],
    //         "inputs"=>[
    //         ],
    //     ];
    // }
    public function getUserProfileForm(){
        return [
            "config" => [
                "method" => "POST",
                "action" => "",
                "id" => "formUserProfile",
                "class" => "formUserprofile",
                "submit" => "Valider"
            ],
            "inputs" => [
                "firstname" => [
                    "type" => "text",
                    "label" => "Votre prénom",
                    "id" => "firstname",
                    "class" => "formInput",
                    "placeholder" => "Johnny",
                    "value" => $this->firstname ?? "",
                    "disabled" => 'disabled'
                ],
                "lastname" => [
                    "type" => "text",
                    "label" => "Votre nom",
                    "id" => "lastname",
                    "class" => "formInput",
                    "placeholder" => "Chen",
                    "value" => $this->lastname ?? "",
                    "disabled" => 'disabled'
                ],
                "email" => [
                    "type" => "email",
                    "label" => "Votre email)",
                    "id" => "email",
                    "class" => "formInput",
                    "placeholder" => "nom@gmail.com",
                    "value" => $this->getEmail() ?? '',
                    "error" => "Votre email doit faire entre 8 et 320 caractères",
                    "required" => true,
                    "disabled" => 'disabled'
                ],
                "oldPassword" => [
                    "type" => "password",
                    "label" => "Votre mot de passe actuel",
                    "id" => "password",
                    "class" => "formInput",
                    "placeholder" => "",
                    "error" => "Votre mot de passe doit faire au minimum 8 caractères",
                    "required" => false
                ],
                "password" => [
                    "label" => "Votre nouveau mot de passe",
                    "type" => "password",
                    "id" => "passwordd",
                    "class" => "formInput",
                    "placeholder" => "",
                    "error" => "Votre mot de passe doit faire au minimum 8 caractères",
                    "required" => false
                ],
                "passwordConfirm" => [
                    "label" => "Confirmation",
                    "type" => "password",
                    "confirm" => "password",
                    "id" => "passwordConfirm",
                    "class" => "formInput",
                    "placeholder" => "",
                    "error" => "Votre mot de mot de passe de confirmation ne correspond pas",
                    "required" => false
                ],
            ]
        ];
    }
}