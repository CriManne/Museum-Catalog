<?php $this->layout('layouts::artifact_form', ['title' => $title, 'user' => $user]) ?>

<div class="form-outline mb-4">
    <label class="form-label" for="Title">Titolo</label>
    <input type="text" name="Title" id="Title" class="form-control" required />
</div>
<div class="form-outline mb-4">
    <label class="form-label" for="MagazineNumber">Numero di rivista</label>
    <input type="number" min="1" max="9999999" name="MagazineNumber" id="MagazineNumber" class="form-control" required />
</div>
<div class="form-outline mb-4">
    <label class="form-label" for="Year">Anno</label>
    <input type="number" min="1500" max="2500" name="Year" id="Year" class="form-control" required />
</div>
<div class="form-outline mb-4">
    <label class="form-label" for="PublisherID">Casa editrice</label>
    <select id="PublisherID" name="PublisherID" class="form-select" aria-label="Seleziona un tipo di periferica" required>
        <option value="" hidden selected>Seleziona la casa editrice</option>
    </select>
</div>
<div class="form-outline mb-4">
    <label class="form-label" for="Note">Note</label>
    <textarea class="form-control" name="Note" id="Note" rows="3"></textarea>
</div>
<div class="form-outline mb-4">
    <label class="form-label" for="Url">URL</label>
    <input type="text" name="Url" id="Url" class="form-control" />
</div>
<div class="form-outline mb-4">
    <label class="form-label" for="Tag">Tag</label>
    <input type="text" name="Tag" id="Tag" class="form-control" />
</div>
<div class="form-outline mb-4" id="images-outer-container"></div>
<div class="mb-3">
    <label for="images" class="form-label">Immagini del reperto</label>
    <input class="form-control" name="images[]" type="file" multiple="multiple" accept="image/*">
</div>
<input type='hidden' name='category' value='Peripheral'>

<?php $this->push('scripts_inner') ?>
<script>
    let urlPublisher = "/api/generic/components?category=Publisher";
    loadSelect(urlPublisher, "#PublisherID");
</script>
<?php $this->end() ?>