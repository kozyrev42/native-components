<?php
require_once('init.php');

// в условии проверяем, была-ли отправлена форма, если проверка пройдена, начинаем функционал валидации
if (Input::exists()) { 
    // проверяем, токен юзера, тот ли который мы ему передали в скрытый инпут
    if(Token::check(Input::get('token'))) { 
        // создание нового Объекта, которым будем пользоваться
        $validate = new Validate();

        // методу Объекта передаём данные на обработку: массив $_POST(что будем проверять), массив с критериями(граничиными условиями) на проверку (на что будем проверять)
        $validation = $validate->check($_POST, [
            // ключи элементов также соответствуют ключам из массива $_POST
            'username' => [             // массив содержит правила для проверки
                'required' => true,     // поле обязательно для заполнения
                'min' => 2,
                'max' => 15
            ],
            'email' => [ 
                'required' => true,             // поле обязательно для заполнения
                'email' => true,
                'unique' => 'level-two-users'   // email должен быть уникальным в таблице 'level-two-users'
            ],
            'password' => [
                'required' => true,             // поле обязательно для заполнения
                'min' => 3
            ],
            'password_again' => [
                'required' => true,              // поле обязательно для заполнения
                'matches' => 'password'          // должен совпадать со значение поля 'password'
            ],
        ]);

        if ($validation->passed()) {
            // если метод вернёт "true", значит валидация пройдена

            // создавая new User, автоматический создаётся подключение к базе
            $user = new User;
            // запись в базу введённых в форме данных
            $user->create([
                // в массив помещаем поля для заполнения и данные из формы
                'email'=> Input::get('email'),
                'username'=> Input::get('username'),
                'password'=> password_hash(Input::get('password'),PASSWORD_DEFAULT)
            ]);
            
            // методу передаём ключ-вид-сообщения, и само сообщение
            Session::flash('success','успех');
            
            //Redirect::to('test.php');
            //Redirect::to('404');
        } else {
            // иначе возвращаем ошибки
            // переборка массива с ошибками
            foreach ($validation->errors() as $error) {
                echo $error . "<br>";
            }
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
        <label for="email">Email</label>
        <input type="text" name="email" value="<?php echo Input::get('email')?>">
    </div>
    
    <div class="field">
        <label for="">Password</label>
        <input type="text" name="password">
    </div>

    <div class="field">
        <label for="">Password again</label>
        <input type="text" name="password_again">
    </div>

    <!-- отправляем пользователю форму со сгенерированным уникальным токеном -->
    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">

    <div class="field">
        <button type="submit">Submit</button>
    </div>
</form>