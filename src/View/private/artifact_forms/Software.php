<?php $this->layout('layouts::artifact_form', ['title' => $title, 'user' => $user]) ?>

<h3 class="text-center"><?= $title ?></h3>
<div id="alert-container"></div>

<form id='artifact-form' method="POST" action="/api/artifacts" enctype="multipart/form-data">
    <div class="form-outline mb-4">
        <label class="form-label" for="ObjectID">IDENTIFICATIVO CATALOGO</label>
        <input type="text" minlength="20" maxlength="20" name="ObjectID" id="ObjectID" class="form-control" required />
    </div>
    <div class="form-outline mb-4">
        <label class="form-label" for="Title">Titolo</label>
        <input type="text" name="Title" id="Title" class="form-control" required />
    </div>
    <div class="form-outline mb-4">
        <select id="OsID" name="OsID" class="form-select" aria-label="Seleziona un sistema operativo" required>
            <option value="" hidden selected>Seleziona un sistema operativo</option>
        </select>
    </div>
    <div class="form-outline mb-4">
        <select id="SoftwareTypeID" name="SoftwareTypeID" class="form-select" aria-label="Seleziona una tipologia software" required>
            <option value="" hidden selected>Seleziona una tipologia software</option>
        </select>
    </div>
    <div class="form-outline mb-4">
        <select id="SupportTypeID" name="SupportTypeID" class="form-select" aria-label="Seleziona un supporto" required>
            <option value="" hidden selected>Seleziona un supporto</option>
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
    <input type='hidden' name='category' value='Software'>
    <input type='submit' class='btn btn-primary' id='btn-submit'>
    <input type='reset' class='btn btn-info' id='btn-reset'>
</form>

<?php $this->push('scripts') ?>
<script>
    let urlOs = "/api/generic/components?category=Os";
    loadSelect(urlOs, "#OsID");
    let urlSoftwareType = "/api/generic/components?category=SoftwareType";
    loadSelect(urlSoftwareType, "#SoftwareTypeID");
    let urlSupportType = "/api/generic/components?category=SupportType";
    loadSelect(urlSupportType, "#SupportTypeID");
</script>
<?php $this->end(); ?>