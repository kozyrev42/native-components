<?php
class Redirect
{
    public static function to($location = null)
    {
        // если аргумет содержит Локацию, вызываем заголовок для перевода в это локацию
        if($location) {
            // если хотим чтобы сработала 404 ошибка, передаём методу 404
            if(is_numeric($location)) {
                switch ($location) {
                    case 404:
                        header('HTTP/1.0 404 Not Found');
                        require_once('errors/404.php');
                        exit;
                    break;
                }
            }

            header('Location:' . $location);
        }
    }
}
