<?php
session_start();
$this->layout('layouts::layout', ['title' => 'Login'])
?>

<?= "Welcome back " . $this->e($user->firstname) . "!"; ?>

<?php

$_SESSION['user_email'] = $this->e($user->Email);
$_SESSION['privilege'] = $this->e($user->Privilege);

if($this->e($user->Privilege)==="1"){
    echo "<a href='/private/admin'>Go to admin settings</a>";
}
?>
GESTIONE REPERTI!