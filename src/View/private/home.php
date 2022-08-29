<?php $this->layout('layouts::layout', ['title' => 'Login', 'styles'=>[]]) ?>

<?= "Welcome back ".$this->e($user->firstname)."!";
