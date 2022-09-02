<?php $this->layout('layouts::layout', ['title' => 'Login']) ?>

<div>
    <form action="/private" method="POST">

        <input type="text" name="email">
        <input type="password" name="password">
        <input type="submit" name="submitLogin">

    </form>
</div>
