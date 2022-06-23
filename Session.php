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

    public static function flash($name, $string = '') {
        // бращаемся к статичному методу, поэтому используем self::, без создания экземпляра класс, в контексте класса
        // $name - принимаем ключ, для записи в сессию переменной под этим именем
        // $string - строка сообщения

        // если сессия по ключу существует, и сообщение в нём не пустое
        if(self::exists($name) && self::get($name) !== '') {
            // тогда записываем в $session - это сообщение, для одноразового использования
            $session = self::get($name);
            // после записи удаляем сессию
            self::delete($name);
            // возвращаем сообщение из сессии
            return $session;
        } else {
            // иначе запись сообщения в сессию
            self::put($name, $string);
        }
    }
}