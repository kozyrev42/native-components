<?php

class Validate {
    private $passed = false;    // свойство хранит успешность валидации, по умолчанию валидация не пройдена
    private $errors = [];       // 
    private $db = null;         // 

    public function __construct() {
        // вызов метода, который вернёт подключение к базе PDO
        // и всё это происходит при создании экземпляра Validate
        $this->db = Database::getInstance();
    }

    // метод перебирает массивы, $source - массив содержит что будем проверять. $items - массив содержит критерии проверки, граниченные условия
    public function check($source, $items = []) {

        // циклом проходим по массиву с критериями
        foreach($items as $item => $rules) { // обращаемся к вложенным массивам, у меня их 3
            foreach($rules as $rule => $rule_value) { // обращаемся к значениям по ключам в этих вложенных массивах
                
                // в $value в каждой итерации будет приходит новое значение из формы, для проверки с заданными критериями
                $value = $source[$item]; 

                if($rule == 'required' && empty($value)) { // заполнено ли поле, обязательное к заполнению
                    $this->addError("{$item} обязательно к заполнению"); // если пустое, добавляем ошибку в массив с ошибками
                } else if(!empty($value)) { // если поле не пустое, сравниваем значение с критериями
                    switch ($rule) {
                        case 'min': // если критерий не пройден, добавляем ошибку
                            if(strlen($value) < $rule_value) {
                                $this->addError("{$item} должно быть минимум {$rule_value} символа.");
                            }
                        break;

                        case 'max':
                            if(strlen($value) > $rule_value) {
                                $this->addError("{$item} должно быть не более {$rule_value} символов.");
                            }
                        break;

                        case 'matches':
                            if($value != $source[$rule_value]) {
                                $this->addError("{$rule_value} должны соответствовать {$item}");
                            }
                        break;

                        case 'unique': 
                            // делаем запрос в базу, чтобы проверить, если ли в таблице, введённое юзером значение
                            $check = $this->db->get($rule_value, [$item, '=', $value]); 
                            if($check->count()) {
                                $this->addError("{$item} уже существует.");
                            }
                        break;

                        case 'email':
                            if(!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                                $this->addError("{$item} это не электронное письмо");
                            }
                        break;
                    }
                }
            }
        }

        // если массив с ошибками пустой, значит поднимаем флаг, о пройденной валидации
        if(empty($this->errors)) {
            $this->passed = true;
        }

        return $this;
    }

    // при вызове, в массив добавляется значение из аргумента
    public function addError($error) {
        $this->errors[] = $error; 
    }

    // метод возвращает массив с ошибками
    public function errors() {
        return $this->errors;
    }

    // метод сообщает булевое, о прохождении валидации
    public function passed() {
        return $this->passed;
    }
}