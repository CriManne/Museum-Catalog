<?php $this->layout('layouts::dashboard_layout', ['title' => 'Add software', 'user' => $user]) ?>

<div class="container-fluid p-0 gap-2 align-items-center w-100" id="main-container">
    <p><a href="/private?page=add_artifact">
            <- Go back</a>
    </p>
    <h3 class="text-center">Aggiungi software</h3>
    <div id="alert-container"></div>
    <form id='add-form' method="POST" action="/api/artifacts" enctype="multipart/form-data">
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
        <div class="mb-3">
            <label for="images" class="form-label">Immagini del reperto</label>
            <input class="form-control" name="images[]" type="file" multiple="multiple" accept="image/*">            
        </div>
        <input type='hidden' name='category' value='Software'>
        <input type='submit' class='btn btn-primary' id='btn-submit'>
    </form>
</div>
<?php $this->push('scripts') ?>
<script src="/api/scripts?filename=fill_select_component.js"></script>
<script>
    let urlOs = "/api/component/search?category=os";
    loadSelect(urlOs, "#OsID");
    let urlSoftwareType = "/api/component/search?category=softwaretype";
    loadSelect(urlSoftwareType, "#SoftwareTypeID");
    let urlSupportType = "/api/component/search?category=supporttype";
    loadSelect(urlSupportType, "#SupportTypeID");
    const urlAdd = urlArtifacts;
</script>
<script src="/api/scripts?filename=add_artifact.js"></script>
<?php $this->end() ?>