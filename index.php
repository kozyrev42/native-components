<?php
require_once('init.php');

//var_dump(Session::get(Config::get('session.user_session')));

// нужно автоматический: состояние залогиненого пользователя в Объекте
// то есть при создании объекта, Объект должен содержать, залогинен ли юзер?
$user = new User;

// создаём другого пользователя по id
//$anotherUser = new User(1);
// получить определенного пользователя "другойПользователь", передаём (id)
// echo $anotherUser->data()->username;

// если юзер залогинен
if($user->isLoggedIn()) {
    echo "Hi, <a href='#'>{$user->data()->username}</a>";
    echo "<p><a href='logout.php'>Выйти</a></p>";
    echo "<p><a href='update.php'>Обновить профиль</a></p>";
    echo "<p><a href='changepassword.php'>Обновить пароль</a></p>";

    if($user->hasPermissions('admin')) {
        echo 'Вы вошли как Администратор';
    }

    if($user->hasPermissions('moder')) {
        echo 'Вы вошли как Модератор';
    }

} else {
    echo "<a href='login.php'>Войти</a><br>";
    echo "<a href='register.php'>Зарегистрироваться</a>";
}
