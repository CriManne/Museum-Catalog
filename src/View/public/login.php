<?php

if (!is_session_started()) {
    session_start();
}
unset($_SESSION);
session_destroy();

$this->layout('layouts::layout', ['title' => 'Login']);

if (isset($error)) {
    echo "<h4>" . $error . "</h4>";
}

function is_session_started()
{
    if ( php_sapi_name() !== 'cli' ) {
        if ( version_compare(phpversion(), '5.4.0', '>=') ) {
            return session_status() === PHP_SESSION_ACTIVE ? TRUE : FALSE;
        } else {
            return session_id() === '' ? FALSE : TRUE;
        }
    }
    return FALSE;
}

?>

<div>
    <form action="/private" method="POST">

        <input type="text" name="email">
        <input type="password" name="password">
        <input type="submit" name="submitLogin">

    </form>
</div>