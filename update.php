<?php
require_once 'init.php';

$user = new User;

/* $validate = new Validate();
$validate->check($_POST, [
    'username'  =>  ['required'=>true, 'min'=>2]
]); */

if (Input::exists()) { // проверка на пустоту $_POST
    if (Token::check(Input::get('token'))) { // проверка, что форма от того пользователя, которому сервер отправил форму

        // если форма пришла, работаем с валидацией
        $validate = new Validate();
        $validate->check($_POST, [
            'username'  =>  ['required' => true, 'min' => 2]
        ]);

        // если валидация пройдена, то обновляем
        if ($validate->passed()) {
            $user->update(['username'   =>  Input::get('username')]);
            Redirect::to('index.php');
        } else {
            // вывводим ошибки
            foreach ($validate->errors() as $error) {
                echo $error . '<br>';
            }
        }
    }
}
?>


<form action="" method="post">

    <div class="field">
        <label for="username">Username</label>
        <input type="text" name="username" id="username" value="<?php echo $user->data()->username; ?>">
    </div>

    <div class="field">
        <button type="submit">Обновить</button>
    </div>

    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
</form>