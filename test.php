<?php
// тестовый файл, для вывода flash сообщений
require_once 'Session.php';
session_start();
echo Session::flash('success');
