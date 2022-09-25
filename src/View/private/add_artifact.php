<?php $this->layout('layouts::dashboard_layout', ['title' => 'Add user', 'user' => $user]) ?>
Choose artifact category

<select class="input-select" id="category-select">
    <option selected hidden>Scegli una categoria</option>
</select>
<?php $this->push('scripts') ?>
<script src="/api/scripts?filename=choose_artifact_category.js"></script>
<?php $this->end() ?>