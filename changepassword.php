<?php
require_once 'init.php';

$user = new User;

if (Input::exists()) {
    if (Token::check(Input::get('token'))) {

        // валидация пришедшей формы
        $validate = new Validate();
        $validate->check($_POST, [
            'current_password'  =>  ['required' => true, 'min' => 3],
            'new_password'  =>  ['required' => true, 'min' => 3],
            'new_password_again'  =>  ['required' => true, 'min' => 3, 'matches'  =>  'new_password'],
        ]);

        
        if ($validate->passed()) {  // если валидация успешна
            if (password_verify(Input::get('current_password'), $user->data()->password)) { // если текущий пароль введён верно
                $user->update(['password'   =>  password_hash(Input::get('new_password'), PASSWORD_DEFAULT)]); // обновляем пароль в базе
                Session::flash('success', 'Пароль обновлён.'); 
                Redirect::to('index.php');
            } else {
                echo 'Неверный текущий пароль';
            }
        } else {
            foreach ($validate->errors() as $error) {
                echo $error . '<br>';
            }
        }
    }
}
?>



<form action="" method="post">

    <div class="field">
        <label for="username">Текущий пароль</label>
        <input type="text" name="current_password" id="username">
    </div>

    <div class="field">
        <label for="username">Новый пароль</label>
        <input type="text" name="new_password" id="username">
    </div>

    <div class="field">
        <label for="username">Повторить новый пароль</label>
        <input type="text" name="new_password_again" id="username">
    </div>

    <div class="field">
        <button type="submit">Submit</button>
    </div>

    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
</form>