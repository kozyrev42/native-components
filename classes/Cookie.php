<?php

class Cookie
{   
    // проверка, есть ли Куки в браузере под требуемым именем
    public static function exists($name)
    {
        return (isset($_COOKIE[$name])) ? true : false;
    }

    // получаем Куки
    public static function get($name)
    {
        return $_COOKIE[$name];
    }

    // запись в Куки браузера
    public static function put($name, $value, $expiry)
    {
        if (setcookie($name, $value, time() + $expiry, '/')) {
            return true;
        }

        return false;
    }

    // перезапись, с обнулением куки по времени
    public static function delete($name)
    {
        self::put($name, '', time() - 1);
    }
}
