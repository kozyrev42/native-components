<?php

class User
{
    private $db;
    private $data;
    private $sessionName;
    private $isLoggedIn;
    private $cookieName;

    // автоматически создаём Объект подключения к базе PDO
    public function __construct($user = null) // по умолчанию null, то есть Объект создаётся для Залогиненого Юзера
    // либо может прийти id, юзера для которого создаём объект
    {
        $this->db = Database::getInstance();
        $this->sessionName = Config::get('session.user_session');
        $this->cookieName = Config::get('cookie.cookie_name');

        // если $user = null, то есть, объект для Залогиненого пользователя, нужно из Сессии достать эти данные, и сохранить сюда в класс в $data
        // далее сможем сделать запрос в базу, и получить данные пользователя
        // потом сможем обращаться к Юзеру:    $this->data()->username;
        if (!$user) {
            // если существует сессия, только тогда вытащим пользователя
            if (Session::exists($this->sessionName)) {
                // получаем id текущего залогиненого пользователя
                $user = Session::get($this->sessionName);

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

    public function login($email = null, $password = null, $remember = false)
    {
        // если не передали email, и не передали password, и текущий пользователь существует(есть по нему данные)
        if (!$email && !$password && $this->exists()) {
            // то просто записываем сессию текущему пользователю, логируем
            Session::put($this->sessionName, $this->data()->id);
        } else { 
            $user = $this->find($email); // если запись есть, сохраняем в $user
            if ($user) {
                // сверка паролей
                if (password_verify($password, $this->data()->password)) {
                    // если пароли совпали,id юзера записываем в сессию
                    Session::put($this->sessionName, $this->data()->id);

                    // Если "Запомнить" активировано
                    if ($remember) {
                        // генерируем уникальную строку, для куки пользователя
                        $hash = hash('sha256', uniqid());
                        // проверка, есть у юзера сейчас запись в базе с каким-то hash, что бы не создавать новую запись
                        $hashCheck = $this->db->get('user_sessions', ['user_id', '=', $this->data()->id]);

                        // если записи нет, тогда делаем новую запись в базу с хэшем юзера
                        if (!$hashCheck->count()) {
                            $this->db->insert('user_sessions', [
                                'user_id'   =>  $this->data()->id,
                                'hash'  =>  $hash
                            ]);
                        } else { // если есть запись в базе, сохраняем в переменную, хэш юзера из базы
                            $hash = $hashCheck->first()->hash;
                        }

                        // хэш, который записали в базу, записываем в Куки браузера юзера, + сколько времени хранить куки
                        Cookie::put($this->cookieName, $hash, Config::get('cookie.cookie_expiry'));
                    }

                    // возвращаем успешность логирования
                    return true;
                }
            }
        }
        return false; // значит не правильный логин или пароль
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
        // очищаем переменную сессии и куки
        $this->db->delete('user_sessions', ['user_id', '=', $this->data()->id]);
        Session::delete($this->sessionName);
        Cookie::delete($this->cookieName);

    }

    // проверка, вошел ли юзер, то есть ли данные пользователя в $data
    public function exists() {
        return (!empty($this->data())) ? true : false;
    }


    public function update($fields = [], $id = null) { // если $id = null, значит обновляем текущего пользователя или может прийти $id - другого юзера 

        if(!$id && $this->isLoggedIn()) {
            // если $id не прилетел, и юзер залогинен -> то $id - текущего пользователя
            $id = $this->data()->id;
        }

        //  обновляем запись в базе по $id
        $this->db->update('level-two-users', $id, $fields);
    }

    public function hasPermissions($key = null) {
        //var_dump($this->data()->group_id);exit; // data() - хранит запись из бд юзера, ->group_id - обращаемся к полю
        if($key) {

            $group = $this->db->get('groups', ['id', '=', $this->data()->group_id]);
            
            if($group->count()) {
                $permissions = $group->first()->permissions; // сохраняем данные ячейки по указанию на поле
                $permissions = json_decode($permissions, true); // json -> переводим в -> массив 

                if($permissions[$key]) { // если массив $permissions по ключу $key(который прилетант при вызове) возвращает "1", то выполняем блок
                    return true;
                }
            }
        }
        return false;
    }
}
