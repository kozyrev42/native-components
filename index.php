<?php
require_once('init.php');

//var_dump(Session::get(Config::get('session.user_session')));

// нужно автоматический: состояние залогиненого пользователя в Объекте
// то есть при создании объекта, Объект должен содержать, залогинен ли юзер?
// например создаём Объект
// это залогиненый
$user = new User;
//echo $user->data()->username;


// создаём другого пользователя по id
$anotherUser = new User(1);
// получить определенного пользователя "другойПользователь", передаём (id)
// echo $anotherUser->data()->username;


// если юзер залогинен
if($user->isLoggedIn()) {
    echo "залогинен"."<br>";
    echo "Hi, <a href='#'>{$user->data()->username}</a>";
    echo "<p><a href='logout.php'>Выйти</a></p>";
} else {
    echo "<a href='login.php'>Войти</a><br>";
    echo "<a href='register.php'>Зарегистрироваться</a>";
}
