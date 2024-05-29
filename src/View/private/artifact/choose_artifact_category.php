<?php $this->layout('layouts::dashboard_layout', ['title' => $title, 'user' => $user]) ?>
<div class="container d-flex flex-column align-items-center my-5 p-2 gap-5">
    <h3 class="text-center">Choose the artifact's category</h3>

    <select id="artifact-category-select" class="form-select" aria-label="Choose the category">
        <option selected hidden>Choose the category</option>
    </select>
</div>
<?php $this->push('scripts') ?>
<script src="/api/scripts?filename=/artifact/choose_artifact_category.js"></script>
<?php $this->end() ?>