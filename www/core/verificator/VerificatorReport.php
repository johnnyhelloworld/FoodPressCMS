<?php

namespace App\Core\verificator;

class VerificatorReport
{
    public static function validate($config, array $data): array
    {
        if (count($config["inputs"]) != count($_POST) && count($config["inputs"]) != count($_GET)) {
            die("Tentative de hack");
        }

        $errors = [];
        if (!$data['message'])
            $errors['message'] =  'Veuillez renseigner un message';

        if (!$data['email'])
            $errors['email'] =  'Veuillez renseigner un email valide';

        if (strlen($data['email']) > 100)
            $errors['email'] =  'La taille maximum de l\'email est de 100 caractères';

        return $errors;
    }
}