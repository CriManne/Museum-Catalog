<?php $this->layout('layouts::dashboard_layout', ['title' => 'Add book','user'=>$user]) ?>
<p><a href="/private?page=add_artifact">Go back</a></p>
Add book

<form>
    <input value="INSERISCI BOOK">
</form>
<?php $this->push('scripts') ?>
<script src="/api/scripts?filename=add_computer.js"></script>
<?php $this->end() ?>