<?php $this->layout('layouts::layout-page',['title' => $title]) ?>


<?= $error ?? '' ?>
<form action='employee-area.php' method='post'>
    
    <label for='username'>Username:</label>
    <input type='text' name='username' id='username' required>
    <label for='password'>Password:</label>
    <input type='password' name='password' id='password' required>

    <input type='submit' name='btnLoginSubmit'>

</form>
