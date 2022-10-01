<?php $this->layout('layouts::layout', ['title' => "Error"]) ?>

<style>
    <?php include('style.css') ?>
</style>

<div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center">
    <h1><?= $this->e($error_code) ?></h1>
    <h2>UH OH! You're lost.</h2>
    <p><?= $this->e($error_message) ?></p>
    <a href='/' class="btn green">HOME</a>
</div>