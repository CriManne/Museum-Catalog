<?php $this->layout('layouts::dashboard_layout', ['title' => 'Add user','user'=>$user]) ?>
Add artifact
<?php $this->push('scripts') ?>
<script src="/api/adv/scripts?filename=add_user.js"></script>
<?php $this->end() ?>