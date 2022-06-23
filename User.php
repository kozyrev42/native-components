<?php

class User
{
    private $db;

    // автоматически создаём Объект подключения к базе PDO
    public function __construct() {
        $this->db = Database::getInstance();
    }

    // пользуемся методом объекта, для записи в таблицу
    public function create($fields = []) {
        $this->db->insert('users', $fields);
    }
}
