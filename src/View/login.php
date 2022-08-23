<?php $this->layout('layout', ['title' => 'Login']) ?>

<div>
    <form action="/login" method="POST">

        <input type="text" name="email">
        <input type="password" name="password">
        <input type="submit" name="submitLogin">

    </form>
</div>
