<?php $this->layout('layouts::artifact_form', ['title' => $title, 'user' => $user]) ?>

<div class="form-outline mb-4">
    <label class="form-label" for="title">Title</label>
    <input type="text" name="title" id="title" class="form-control" required />
</div>
<div class="form-outline mb-4">
    <label class="form-label" for="magazineNumber">Magazine number</label>
    <input type="number" min="1" max="9999999" name="magazineNumber" id="magazineNumber" class="form-control" required />
</div>
<div class="form-outline mb-4">
    <label class="form-label" for="year">Year</label>
    <input type="number" min="1500" max="2500" name="year" id="year" class="form-control" required />
</div>
<div class="form-outline mb-4">
    <label class="form-label" for="publisherId">Publisher</label>
    <select id="publisherId" name="publisherId" class="form-select" aria-label="Select the publisher" required>
        <option value="" hidden selected>Select the publisher</option>
    </select>
</div>
<input type='hidden' name='category' value='Magazine'>

<?php $this->push('scripts_inner') ?>
<script>
    let urlPublisher = "/api/generic/components?category=Publisher";
    loadSelect(urlPublisher, "#publisherId");
</script>
<?php $this->end() ?>