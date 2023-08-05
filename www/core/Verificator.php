<?php

namespace App\Core;

class Verificator
{
    public static function checkForm($config, $data): array
    {
        $errors = [];

        if(count($config["inputs"]) != count($_POST)){
            die("Tentative de hack");
        }

        foreach ($config["inputs"] as $name=>$input)
        {
            if(!isset($data[$name])){
                $errors[]="Il manque le champ :".$name;
            }

            if(!empty($input["required"]) && empty($data[$name]) ){
                $errors[]=$name ." ne peut pas être vide";
            }

            if(!empty($input["type"]=="text") && empty($data[$name])) {
                $errors[]=$name ." ne peut pas être vide";
            }

            if($input["type"]=="email" &&  !self::checkEmail($data[$name])) {
                $errors[]=$input["error"];
            }

            if($input["type"]=="password" &&  !self::checkPassword($data[$name]) && empty($input["confirm"])) {
                $errors[]=$input["error"];
            }

            if( !empty($input["confirm"]) && $data[$name]!=$data[$input["confirm"]]  ){
                $errors[]=$input["error"];
            }
        }
        return $errors;
    }

    public static function checkEmail($email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    public static function checkPassword($pwd): bool
    {
        return strlen($pwd)>=8
            && preg_match("/[0-9]/",$pwd, $result )
            && preg_match("/[A-Z]/",$pwd, $result );
    }
}