<?php

class Input {
// компонент проверяет на пустоту массивы $_POST и $_GET, возвращает булевое
// компонент может возвращать значения из массивов $_POST и $_GET

    public static function exists($type = 'post') { // если 
        switch ($type) {

            case 'post': // кейс выполняется если в переменной $type содержится 'post'
                // проверка на пустоту массива $_POST
                // если массив содержит, функция возвращает true, иначе false
                return (!empty($_POST)) ? true : false;
                // break - не используется, так как необходимо проверить еще условия  
            case 'get': // кейс не выполняется, так как $type в аргументе определён как $type = 'post'
                // проверка на пустоту массива $_GET
                return (!empty($_GET)) ? true : false;
            default:
                // если не сработал ни один кейс
                return false;
            break;  

        }
    }

    // в  аргументе необходимо получить ключ от массива
    // по ключу возвращаем значение из массива
    public static function get($item) {
        if(isset($_POST[$item])) {
            return $_POST[$item];
        } else if(isset($_GET[$item])) {
             return $_GET[$item];
        }

        // возвращаем пустую строчку, если не одно условие не сработало
        return '';
    }
}