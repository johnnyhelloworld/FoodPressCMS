<?php

namespace App\Helpers;

class Slugger
{

    public static function sluggify($recipe_title)
    {
        $chars = [
            'é' => 'e', 'è' => 'e', 'ê' => 'e', 'ë' => 'e', 'Œ' => 'oe', 'Ē' => 'e',
            'À' => 'a', 'à' => 'a', 'â' => 'a', 'ä' => 'a', 'ì' => 'i', 'î' => 'i', 'ï' => 'i',
            'ô' => 'o', 'ö' => 'o', 'ò' => 'o', 'û' => 'u', 'ù' => 'u', 'û' => 'u',
            '&' => '-', '\"' => '', '\'' => '', '§' => '', '!' => '', '?' => '', '%' => '',
            '$' => '', '€' => '', '£' => '', '/' => '-', '(' => '-', ')' => '-', '_' => '-',
            '`' => '-', '+' => '-', '=' => '-', ',' => '-', ';' => '-', ':' => '-', '^' => '',
            '<' => '', '>' => '', '@' => '', '#' => '', '°' => '', '*' => '', ' ' => '-'
        ];


        $recipe_title = strtolower(trim(htmlspecialchars($recipe_title)));

        $recipe_chars = str_split($recipe_title);

        $expected_chars = [];
        for ($i = 0; $i < count($recipe_chars); $i++) {
            foreach ($chars as $key => $value) {
                if ($recipe_chars[$i] == $key) {
                    $expected_chars[] = $value;
                }
            }
            $expected_chars[] = $recipe_chars[$i];
        }

        $slug =  implode('', $expected_chars);
        $slug = str_replace(' ', '', $slug);
        return $slug;
    }
}