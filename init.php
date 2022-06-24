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
        'token_name' => 'token'
    ]
];
