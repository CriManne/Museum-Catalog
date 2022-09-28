<?php $this->layout('layouts::dashboard_layout', ['title' => 'Reperti','user'=>$user]) ?>

<?php $this->insert('reusable::view_artifacts'); ?>

<?php $this->push('scripts') ?>
<script src="/resources/js/search_artifacts.js"></script>
<script src="/api/scripts?filename=view_artifacts.js"></script>
<?php $this->end() ?>