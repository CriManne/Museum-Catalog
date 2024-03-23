<?php

$this->layout('layouts::dashboard_layout', ['title' => $title, 'user' => $user])
?>

<h1 class="mt-4">Dashboard<?= " di " . $this->e($user->firstname) . "!"; ?></h1>
<div class='w-100 text-lg-center'>
    <h3>Seleziona una voce dal menu!</h3>
</div>