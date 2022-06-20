<?php

require_once ('Database.php');

//$users = Database::getInstance()->query("SELECT * FROM `level-two-posts` WHERE title IN (?,?)" , ['1111','ха']);
$users = Database::getInstance()->get('email_list', ['id', '=', '2']);
//$users = Database::getInstance()->delete('level-two-posts', ['id', '=', '3']);

// задать вопрос! как я хочу пользоваться методом ?
// хочу 1) прописать таблицу куда занести данные
// хочу 2) отправить данные Ассоциативным массивом, где ключ-это поле, значение по ключу-сохраняемое значение 
/* $users = Database::getInstance()->insert('email_list',[
    'last_name' => ' 22 еще ф',
    'email' => '22 еще е'
]); */

$id = 2;
// хочу изменять данные в записи, по id записи
$users = Database::getInstance()->update('email_list', $id, [
    'last_name' => ' 444 еще ф',
    'email' => '444 еще е'
]);



//var_dump($users->count());

/* if($users->error()) {
    echo "-запрос не прошел-";
} else {
    echo "-запрос выполнен-";
} */



/* foreach ($users->result() as $user) {
    echo '<br/>' . $user->email . '<br/>';
} */
