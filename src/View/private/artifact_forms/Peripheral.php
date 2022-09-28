<?php $this->layout('layouts::artifact_form', ['title' => $title, 'user' => $user]) ?>

<h3 class="text-center"><?= $title ?></h3>
<div id="alert-container"></div>
<form id='artifact-form' method="POST" action="/api/artifacts" enctype="multipart/form-data">
    <div class="form-outline mb-4">
        <label class="form-label" for="ObjectID">IDENTIFICATIVO CATALOGO</label>
        <input type="text" minlength="20" maxlength="20" name="ObjectID" id="ObjectID" class="form-control" required />
    </div>
    <div class="form-outline mb-4">
        <label class="form-label" for="ModelName">Nome modello</label>
        <input type="text" name="ModelName" id="ModelName" class="form-control" required />
    </div>
    <div class="form-outline mb-4">
        <select id="PeripheralTypeID" name="PeripheralTypeID" class="form-select" aria-label="Seleziona un tipo di periferica" required>
            <option value="" hidden selected>Seleziona un tipo di periferica</option>
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
    <input type='submit' class='btn btn-primary' id='btn-submit'>
    <input type='reset' class='btn btn-info' id='btn-reset'>
</form>

<?php $this->push('scripts_inner') ?>
<script>
    let urlPeripheralType = "/api/generic/components?category=PeripheralType";
    loadSelect(urlPeripheralType, "#PeripheralTypeID");
    const urlAdd = urlArtifacts;
</script>
<?php $this->end() ?>