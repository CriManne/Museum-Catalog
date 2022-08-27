<?php $this->layout('layouts::layout', ['title' => "Error"]) ?>

<h1>Error <?= $this->e($error_code) ?>:</h1>
<p>
    <h3><?= $this->e($error_message)?></h3>
</p>