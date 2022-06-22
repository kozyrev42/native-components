<?php
session_start();

class Token {
    // создание и запись в переменную сессии, нового сгенерироного значения
    public static function generate() {
        // Config::get('session.token_name') - берём имя из конфигаруционного массива => 'token'
        // генерируем значение
        return Session::put(Config::get('session.token_name'), md5(uniqid()));
    }

    // метод для сравнения, назначеного токена юзеру, и того который пришел с формой
    public static function check($token) { // на проверку приходит токен из скрытого инпута
        $tokenName = Config::get('session.token_name'); // token

        // проверка: 
        // Session::exists($tokenName) - существует ли такой ключ в сессии
        // $token == Session::get($tokenName) - значение токена от юзера, совпадает ли с назначенным ему токеном
        if(Session::exists($tokenName) && $token == Session::get($tokenName)) {
            // удаление одноразового токена
            Session::delete($tokenName);
            // возращаем успешность проверки
            return true;
        }

        return false;
    }
}