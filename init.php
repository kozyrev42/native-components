<?php
session_start();

require_once('classes/Config.php');
require_once('classes/Database.php');
require_once('classes/Input.php');
require_once('classes/Validate.php');
require_once('classes/Session.php');
require_once('classes/Token.php');
require_once('classes/User.php');
require_once('classes/Redirect.php');
require_once('classes/Cookie.php');

// ипользование $GLOBALS['config'] -> Config::get('mysql.host'); в функцию объекта передаю ('имяМассива.ключКзначению')
$GLOBALS['config'] = [
    'mysql' => [
        'host' => 'localhost',
        'username' => 'root',
        'password' => '',
        'database' => 'my_php',
        'something' => [
            'no' => 'yes'
        ]
    ],
    'session' => [
        'token_name' => 'token',
        'user_session' => 'user' // наименование переменной-сессии 

    ],
    'cookie'    =>  [
        'cookie_name'   =>  'hash', // наименование
        'cookie_expiry' =>  604800  /* время жизни Куки */
    ]

];

// есть ли Куки в браузере с именем 'hash' и не существует активной Сессии
if (Cookie::exists(Config::get('cookie.cookie_name')) && !Session::exists(Config::get('session.user_session'))) {
    // тогда берём текущий Куки
    $hash = Cookie::get(Config::get('cookie.cookie_name'));
    // сверка Куки юзера в браузера и в базе 
    $hashCheck = Database::getInstance()->get('user_sessions', ['hash', '=', $hash]);
    
    // если совпадают
    if ($hashCheck->count()) {
        // создаём объект,  по id
        $user = new User($hashCheck->first()->user_id);
        // логируем юзера по пришедшей записи
        $user->login();
    }
}
