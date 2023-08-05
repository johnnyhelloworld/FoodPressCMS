<?php

namespace App\core;

class Session
{
    public function __construct()
    {
        if(session_status() === 1){
            session_start();
        }else{
            echo "Session déjà initialisée";
        }
    }

    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public function get($key)
    {
        if(array_key_exists($key, $_SESSION)){
            return $_SESSION[$key];
        }
        return null;
    }

    public function getAll()
    {
        return $_SESSION;
    }

    public function clear()
    {
        session_unset();
    }
}