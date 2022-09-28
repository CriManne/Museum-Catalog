<?php $this->layout('layouts::dashboard_layout', ['title' => 'Add peripheral', 'user' => $user]) ?>

<div class="container-fluid p-0 gap-2 align-items-center w-100" id="main-container">
    <p>
        <a href="/private?page=add_artifact" class="btn btn-primary">
            <i class="fa-solid fa-arrow-left mx-2"></i>Go back
        </a>
    </p>
    <h3 class="text-center">Aggiungi periferica</h3>
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
        <div class="mb-3">
            <label for="images" class="form-label">Immagini del reperto</label>
            <input class="form-control" name="images[]" type="file" multiple="multiple" accept="image/*">
        </div>
        <input type='hidden' name='category' value='Peripheral'>
        <input type='submit' class='btn btn-primary' id='btn-submit'>
    </form>
</div>
<?php $this->push('scripts') ?>
<script src="/api/scripts?filename=fill_select_component.js"></script>
<script>
    let urlPeripheralType = "/api/generic/components?category=PeripheralType";
    loadSelect(urlPeripheralType, "#PeripheralTypeID");
    const urlAdd = urlArtifacts;
</script>
<?php if(!isset($_GET['ObjectID'])){ ?>
    <script> const urlForm = urlArtifactCreate; </script>
<?php }else{ ?>
<script> const urlForm = urlArtifactUpdate; </script>
<?php } ?>
<script src="/api/scripts?filename=artifact_form.js"></script>
<?php $this->end() ?>