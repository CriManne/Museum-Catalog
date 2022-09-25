<?php $this->layout('layouts::dashboard_layout', ['title' => 'Add peripheral','user'=>$user]) ?>
<p><a href="/private?page=add_artifact">Go back</a></p>
Add peripheral

<form>
    <input value="INSERISCI PERIPHERAL">
</form>
<?php $this->push('scripts') ?>
<script src="/api/scripts?filename=add_computer.js"></script>
<?php $this->end() ?>