<?php $this->layout('layouts::dashboard_layout', ['title' => 'Add computer','user'=>$user]) ?>
<p><a href="/private?page=add_artifact">Go back</a></p>
Add computer

<form>
    <input value="INSERISCI COMPUTER">
</form>
<?php $this->push('scripts') ?>
<script src="/api/scripts?filename=add_computer.js"></script>
<?php $this->end() ?>