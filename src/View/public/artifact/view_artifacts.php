<?php $this->layout('layouts::homepage', ['title' => 'Artifact']) ?>

<?php $this->insert('reusable::view_artifacts'); ?>

<?php $this->push('scripts') ?>
<script src="/resources/js/util.js"></script>
<script src="/resources/js/search_artifacts.js"></script>
<script src="/resources/js/view_artifacts.js"></script>
<?php $this->end() ?>