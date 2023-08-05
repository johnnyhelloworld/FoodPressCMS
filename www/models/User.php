<?php

namespace App\models;

use App\core\Sql;


class User extends Sql
{
    protected $id;
    protected $firstname = null;
    protected $lastname = null;
    protected $email;
    protected $status = 0;
    protected $password;
    protected $date_created;
    protected $date_updated;
    protected $token = null;
    
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

    /**
     * @return Integer
     */
    public function getDateCreated(): int
    {
        return $this->date_created;
    }

    /**
     * @param Integer $date_created
     */
    public function setDateCreated(Int $date_created): void
    {
        $this->date_created = date("Y-m-d h:i:s", $date_created);
    }

    /**
     * @return Integer
     */
    public function getDateUpdated(): Int
    {
        return $this->date_updated;
    }

    /**
     * @param Integer $date_updated
     */
    public function setDateUpdated(Int $date_updated): void
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

    public function save(): void
    {
        parent::save();
    }
    
    public function getRegisterForm(): array
    {
        return [
            "config"=>[
                "method"=>"POST",
                "action"=>"",
                "id"=>"formRegister",
                "class"=>"formRegister",
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
                ],
                "password"=>[
                    "placeholder"=>"Votre mot de passe ...",
                    "type"=>"password",
                    "id"=>"pwdRegister",
                    "class"=>"formRegister",
                    "required"=>true,
                    "error"=>"Votre mot de passe doit faire au min 8 caratères avec une majuscule et un chiffre"
                ],
                "passwordConfirm"=>[
                    "placeholder"=>"Confirmation ...",
                    "type"=>"password",
                    "id"=>"pwdConfirmRegister",
                    "class"=>"formRegister",
                    "required"=>true,
                    "error"=>"Votre confirmation de mot de passe ne correspond pas",
                    "confirm"=>"password"
                ],
                "firstname"=>[
                    "placeholder"=>"Votre prénom ...",
                    "type"=>"text",
                    "id"=>"firstnameRegister",
                    "class"=>"formRegister",
                    "min"=>2,
                    "max"=>25,
                    "error"=>" Votre prénom doit faire entre 2 et 25 caractères",
                ],
                "lastname"=>[
                    "placeholder"=>"Votre nom ...",
                    "type"=>"text",
                    "id"=>"lastnameRegister",
                    "class"=>"formRegister",
                    "min"=>2,
                    "max"=>100,
                    "error"=>" Votre nom doit faire entre 2 et 100 caractères",
                ]
            ]
        ];
    }
    public function getLoginForm(): array
    {
        return [
            "config"=>[
                "method"=>"POST",
                "action"=>"",
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
                ],
                "password"=>[
                    "placeholder"=>"Votre mot de passe ...",
                    "type"=>"password",
                    "id"=>"pwdRegister",
                    "class"=>"formRegister",
                    "required"=>true,
                ]
            ]
        ];
    }
    public function getExamForm(): array
    {
        return [
            "config"=>[
                "method"=>"POST",
                "action"=>"",
                "id"=>"formExam",
                "class"=>"formExam",
                "submit"=>"Valider"
            ],
            "inputs"=>[
                "email"=>[
                    "placeholder"=>"Votre email ...",
                    "type"=>"email",
                    "id"=>"emailRegister",
                    "class"=>"formRegister",
                    "required"=>true,
                    "error" => "Tu as mal renseigner ton email"
                ],/*
                "genre"=>[
                    "type"=>"radio",
                    "id"=>"radioExam",
                    "class"=>"formExam",
                    "value"=> [
                        "Homme" => "homme",
                        "Femme" => "femme",
                        "Neutre" => "neutre",
                        "Jupiterien" => "jupiterien"
                    ],
                    "checked" => "jupiterien"
                ],
                "avis"=>[
                    "type"=>"checkbox",
                    "id"=>"checkboxExam",
                    "class"=>"formExam",
                    "value"=> [
                        "Oui" => "true",
                        "Non" => "false",
                        "Peut être" => "null"
                    ],
                    "checked" => "null"
                ],
                "pays"=>[
                    "type"=>"select",
                    "id"=>"selectExam",
                    "class"=>"formExam",
                    "value"=> [
                        "France" => "france",
                        "Algérie" => "algérie",
                        "Maroc" => "maroc"
                    ],
                    "selected" => "algérie"
                ],
                "msg"=>[
                    "placeholder"=>"Votre message ...",
                    "type"=>"textarea",
                    "id"=>"textareaExam",
                    "class"=>"formExam"
                ],
                "image"=>[
                    "type"=>"file",
                    "accept"=>["png","jpg","gif"],
                    "id"=>"fileExam",
                    "class"=>"formExam",
                    "multiple"=>true,
                    "error" => "Mauvais format de fichier (png,jpg,gif)"
                ],*/
                "g-recaptcha-response"=>[
                    "type"=>"captcha",
                    "error" => "Il faut renseigner le captcha"
                ]
            ]
        ];
    }
}