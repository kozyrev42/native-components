<?php
// функциональный класс для работы с базой данных
class Database
{
    private static $instance = null; //  доступно в контексте класс
    private $pdo, $query, $error = false, $result, $count;

    private function __construct()
    {
        try {
            // область перехвата исключения 
            $this->pdo = new PDO("mysql:host=127.0.0.1;dbname=my_php;charset=utf8", "root", "");
            echo "-подключение к базе успешно-";
        } catch (PDOException $exception) { // PDOException - представляет ошибку, вызванную PDO, если она возникает
            // завершение скрипта с выводом ошибки
            die($exception->getMessage());
        }
    }

    // помещаем создание экземпляра класса в публичный статичный метод
    public static function getInstance()
    {
        // проверяем, создан ли Объект-подключения, если не создан > создаём
        if (!isset(self::$instance)) {
            // помещаем созданный Объект в Приватное статичное свойство, для дальнейшей проверки
            self::$instance = new Database();
        }

        // возращаем объект PDO, созданный к конструкторе
        return self::$instance;
    }

    public function query($sql, $params = [])
    {
        //var_dump($params); die;
        $this->error = false;   // для сброса флага
        $this->query = $this->pdo->prepare($sql);

        // $params - параметры для запроса
        if (count($params)) {
            $i = 1;
            foreach ($params as $param) {
                // привязываем каждый прилетевший параметр к параметру функции, начиная с 1
                $this->query->bindValue($i, $param);
                $i++;
            }
        }

        // выполняем запрос и сразу проверяем выполнен ли он
        if (!$this->query->execute()) {
            $this->error = true;    // флаг на ошибку
        } else {
            // результат придёт в виде объекта, который сохраняем в свойство
            $this->result = $this->query->fetchAll(PDO::FETCH_OBJ);
            // метод считает количество вернувшихся записей
            $this->count = $this->query->rowCount();
        }

        // возращаем Объект, содержащий результаты запроса
        return $this;
    }

    public function error()
    {
        return $this->error;
    }

    public function result()
    {
        return $this->result;
    }

    public function count()
    {
        return $this->count;
    }

    // принимаем: таблицу, условие запроса
    public function get($table, $where = [])
    {
        $this->action('SELECT *', $table, $where);
        return $this;
    }

    public function delete($table, $where = []) 
    {
        $this->action('DELETE', $table, $where);
        return $this;
    }

    public function action($action, $table, $where = []) 
    {
        // выполним проверку массива
        if (count($where) === 3) {

            $operators = ['=','>','<','<=','>='];

            $field = $where[0];
            $operator = $where[1];
            $value = $where[2];

            if(in_array($operator, $operators)) {
                $sql = "$action FROM `$table` WHERE $field $operator ?";
                $this->query($sql, [$value]);
                return $this;
            }

        } else {
            return false;
        }
    }
}
