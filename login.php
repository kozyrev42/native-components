<?php
require_once 'init.php';

// в условии проверяем, была-ли отправлена форма
if (Input::exists()) {
    // проверяем, токен юзера, тот ли который мы ему передали в скрытый инпут
    if (Token::check(Input::get('token'))) {
        // создаём объект валидации, для использования его методов
        $validate = new Validate();

        // методу Объекта передаём данные на обработку: массив $_POST(что будем проверять), массив с критериями(граничиными условиями) на проверку (на что будем проверять)
        $validation = $validate->check($_POST, [
            // ключи элементов также соответствуют ключам из массива $_POST
            'email' => [
                'required' => true,             // поле обязательно для заполнения
                'email' => true
            ],
            'password' => [
                'required' => true,             // поле обязательно для заполнения
            ],
        ]);

        //если валидация пройдена, будем логинится
        if ($validation->passed()) {
            // если валидация пройдена, будем логинится
            $user = new User;
            // результат логирования, сохраняем в переменную, булевое
            $login = $user->login(Input::get('email'), Input::get('password'));

            if($login) {
                // логирование успешно
                Redirect::to('index.php');
            } else {
                echo "логирование без успешно";
            }
        } else {
            // иначе выведем ошибки
            foreach ($validation->errors() as $error){
                echo $error . "<br>";
            }
        }
    }
}
?>

<!-- отправляем форму в этот же файл -->
<form action="" method="post">
    <div class="field">
        <label for="email">Email</label>
        <input type="text" name="email" value="<?php echo Input::get('email') ?>">
    </div>

    <div class="field">
        <label for="">Password</label>
        <input type="text" name="password">
    </div>

    <!-- <div class="field">
        <input type="checkbox" name="remember" id="remember">
        <label for="remember">Remember me</label>
    </div> -->

    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
    <div class="field">
        <button type="submit">Submit</button>
    </div>
</form>