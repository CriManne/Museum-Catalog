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
        <label class="form-label" for="HddSize">Dimensioni hard dirsk</label>
        <input type="text" name="HddSize" id="HddSize" class="form-control" required />
    </div>
    <div class="form-outline mb-4">
        <label class="form-label" for="Year">Anno</label>
        <input type="number" min="1500" max="2500" name="Year" id="Year" class="form-control" required />
    </div>
    <div class="form-outline mb-4">
        <select id="CpuID" name="CpuID" class="form-select" aria-label="Seleziona una cpu" required>
            <option value="" hidden selected>Seleziona la cpu</option>
        </select>
    </div>
    <div class="form-outline mb-4">
        <select id="RamID" name="RamID" class="form-select" aria-label="Seleziona una ram" required>
            <option value="" hidden selected>Seleziona la ram</option>
        </select>
    </div>
    <div class="form-outline mb-4">
        <select id="OsID" name="OsID" class="form-select" aria-label="Seleziona un sistema operativo" required>
            <option value="" hidden selected>Seleziona il sistema operativo</option>
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
    <input type='hidden' name='category' value='Computer'>
    <input type='submit' class='btn btn-primary' id='btn-submit'>
    <input type='reset' class='btn btn-info' id='btn-reset'>
</form>

<?php $this->push('scripts_inner') ?>
<script>
    let urlCpu = "/api/generic/components?category=Cpu";
    loadSelect(urlCpu, "#CpuID");
    let urlRam = "/api/generic/components?category=Ram";
    loadSelect(urlRam, "#RamID");
    let urlOs = "/api/generic/components?category=Os";
    loadSelect(urlOs, "#OsID");
</script>
<?php $this->end() ?>