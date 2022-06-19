<?php

require_once ('Database.php');

//$users = Database::getInstance()->query("SELECT * FROM `level-two-posts` WHERE title IN (?,?)" , ['1111','ха']);
$users = Database::getInstance()->get('level-two-posts', ['id', '=', '5']);
//$users = Database::getInstance()->delete('level-two-posts', ['id', '=', '3']);


//var_dump($users->count());

if($users->error()) {
    echo "-запрос не прошел-";
} else {
    echo "-запрос выполнен-";
}



foreach ($users->result() as $user) {
    echo '<br/>' . $user->title . '<br/>';
}
