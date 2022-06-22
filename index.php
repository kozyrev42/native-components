<?php
require_once('Database.php');
require_once('Config.php');

/*  -----------  Database  ------------ */

//$users = Database::getInstance()->query("SELECT * FROM `level-two-posts` WHERE title IN (?,?)" , ['1111','ха']);
//$users = Database::getInstance()->get('email_list', ['id', '=', '2']);
//$users = Database::getInstance()->delete('level-two-posts', ['id', '=', '3']);

// задать вопрос! как я хочу пользоваться методом ?
// хочу 1) прописать таблицу куда занести данные
// хочу 2) отправить данные Ассоциативным массивом, где ключ-это поле, значение по ключу-сохраняемое значение 
/* $users = Database::getInstance()->insert('email_list',[
    'last_name' => ' 22 еще ф',
    'email' => '22 еще е'
]); */

//$id = 2;
// хочу изменять данные в записи, по id записи
/* $users = Database::getInstance()->update('email_list', $id, [
    'last_name' => ' 444 еще ф',
    'email' => '444 еще е'
]); */


//var_dump($users->count());

/* if($users->error()) {
    echo "-запрос не прошел-";
} else {
    echo "-запрос выполнен-";
} */


/* foreach ($users->result() as $user) {
    echo '<br/>' . $user->email . '<br/>';
} */



/* ----------- Config --------------*/
// нужно создать функционал, чтобы доставать данные из массивов любой вложенность, по ключю через точку "."
// у кода есть смысловая облочка, которую нужно понимать, зачем она нужна
// в ооп нужно читать код блочно, а не процедурно
$GLOBALS['config'] = [
    'mysql' => [
        'host' => 'localhost',
        'username' => 'root',
        'password' => '',
        'database' => '',
        'something' => [
            'no' => 'yes'
        ]
    ]
];


// пользоваться буду так:
// в функцию объекта передаю ('имяМассива.ключКзначению')
//echo Config::get('mysql.host');
//var_dump(Config::get('mysql.something.no'));
//echo Config::get('mysql.host');
?>

<?php
/* ---------- Validation ---- Input ----------- */
// используем два компонента

// в условии проверяем, была-ли отправлена форма
// если проверка пройдена, начинаем функционал валидации
if(Input::exists()){
    // создание нового Объекта, которым будем пользоваться
    $validate = new Validate();

    // методу Объекта передаём данные на обработку: массив $_POST(что будем проверять), массив с правилами на проверку (на что будем проверять)
    $validation = $validate->check($_POST, [
        'username' => [ // массив содержит правила для проверки
            'required' => true,
            'min' => 2,
            'max' => 15,
            'unique' => 'users' // username должен быть уникальным в таблице 'users'
        ],
        'password' => [
            'required' => true,
            'min' => 3
        ],
        'password_again' => [
            'required' => true,     // повторить пароль обязательно
            'matches' => 'password' // должен совпадать со значение поля 'password'
        ],
    ]);

    if ($validation->passed()) {
        echo "валидация пройдена";
    } else {
        // иначе возвращаем ошибки
        // переборка массива с ошибками
        foreach($validation->errors() as $error){
            echo $error . "<br>";
        }
    }
}
?>


<form action="" method="post">
    <div class="field">
        <label for="">Username</label>
        <input type="text" name="username" value="<?php echo Input::get('username') ?>">
        <!-- метод возратит введеное ранее username, или пустую строку -->
    </div>

    <div class="field">
        <label for="">Password</label>
        <input type="text" name="password">
    </div>

    <div class="field">
        <label for="">Password again</label>
        <input type="text" name="password_again">
    </div>

    <div class="field">
        <button type="submit">Submit</button>
    </div>
</form>