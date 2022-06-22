<?php

class Session {
    // создание или запись в переменную сессии $name значения $value
    public static function put($name, $value) {
        return $_SESSION[$name] = $value;
    }

    // проверка существования переменной-в-сессии
    public static function exists($name) {
        return (isset($_SESSION[$name])) ? true : false;
    }

    // удаление переменной сессии
    public static function delete($name) {
        if(self::exists($name)) {
            unset($_SESSION[$name]);
        }
    }

    // получение по имени переменной сессии
    public static function get($name) {
        return $_SESSION[$name];
    }

    /* public static function flash($name, $string = '') {
        if(self::exists($name) && self::get($name) !== '') {
            $session = self::get($name);
            self::delete($name);
            return $session;
        } else {
            self::put($name, $string);
        }
    } */
}