<?php

namespace core\main;

class Session
{
    public function init()
    {
        session_start();
    }

    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * @param $key
     * @param bool $one_time
     * @return null|mixed
     */
    public function get($key, $one_time = false)
    {
        if (isset($_SESSION[$key])) {
            $value = $_SESSION[$key];
            if ($one_time)
                unset($_SESSION[$key]);
            return $value;
        }

        return null;
    }
}