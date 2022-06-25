<?php

class User
{
    private $db;
    private $data;
    private $session_name;
    private $isLoggedIn;

    // автоматически создаём Объект подключения к базе PDO
    public function __construct($user = null) // по умолчанию null, то есть Объект создаётся для Залогиненого Юзера
    // либо может прийти id, юзера для которого создаём объект
    {
        $this->db = Database::getInstance();
        $this->session_name = Config::get('session.user_session');

        // если $user = null, то есть, объект для Залогиненого пользователя, нужно из Сессии достать эти данные, и сохранить сюда в класс в $data
        // далее сможем сделать запрос в базу, и получить данные пользователя
        // потом сможем обращаться к Юзеру:    $this->data()->username;
        if (!$user) {
            // если существует сессия, только тогда вытащим пользователя
            if (Session::exists($this->session_name)) {
                // получаем id текущего залогиненого пользователя
                $user = Session::get($this->session_name);

                // сохраняем пользователя в $data
                // передаём (id),   возвращается true/false
                if ($this->find($user)) {
                    // значит от лица данного объекта пользователь залогинен
                    $this->isLoggedIn = true;
                } else {
                    // логаут
                }
            }
        } else {
            $this->find($user);
        }
    }

    // пользуемся методом объекта, для записи в таблицу
    public function create($fields = [])
    {
        // вызов метода для записи в базу
        $this->db->insert('level-two-users', $fields);
    }

    public function login($email = null, $password = null)
    {
        // если $email получили, проверим есть ли он базе
        if ($email) {
            // если запись есть, сохраняем в $user
            $user = $this->find($email);
            if ($user) {
                // сверка паролей
                if (password_verify($password, $this->data()->password)) {
                    // если пароли совпали,id юзера записываем в сессию
                    Session::put($this->session_name, $this->data()->id);
                    // возвращаем успешность логирования
                    return true;
                }
            }
        }
        return false;
    }

    // метод ищет пользователя в базе по id или email, сохраняем его в data
    public function find($value = null)
    {
        // если передали Цифры, значит пришел id
        if (is_numeric($value)) {
            // делаем запрос по айдишнику
            $this->data = $this->db->get('level-two-users', ['id', '=', $value])->first();
        } else {
            // иначе делаем запрос по email
            $this->data = $this->db->get('level-two-users', ['email', '=', $value])->first();
        }

        // если записалось, true
        if ($this->data) {
            return true;
        }
        return false;
    }

    // метод возвращает свойство
    public function data()
    {
        return $this->data;
    }

    public function isLoggedIn()
    {
        // возвращаем состояние Логирования
        return $this->isLoggedIn;
    }

    public function logout()
    {
        // очищаем переменную сессии
        return Session::delete($this->session_name);
    }
}
