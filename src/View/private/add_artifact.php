<?php $this->layout('layouts::dashboard_layout', ['title' => 'Add user', 'user' => $user]) ?>
<div class="container d-flex flex-column align-items-center my-5 p-2 gap-5">
    <h3 class="text-center">Scegli una categoria di reperto</h3>

    <select id="category-select" class="form-select" aria-label="Seleziona una categoria di reperto">
        <option selected hidden>Scegli una categoria</option>
    </select>
</div>
<?php $this->push('scripts') ?>
<script src="/api/scripts?filename=choose_artifact_category.js"></script>
<?php $this->end() ?>