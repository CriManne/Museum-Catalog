<?php $this->layout('layouts::dashboard_layout', ['title' => 'Add magazine', 'user' => $user]) ?>

<?php $this->push('styles') ?>
<link rel="stylesheet" href="/resources/css/image-overlay.css">
<?php $this->end() ?>
<div class="container-fluid d-flex flex-column p-0 gap-2 align-items-center" id="loading-container">
    <div class="spinner-border mx-auto" role="status"></div>
</div>
<div class="container-fluid p-0 gap-2 align-items-center w-100 d-none" id="main-container">
    <p>
        <?php if (!isset($_GET["id"])) { ?>
            <a href="/private?page=add_artifact" class="btn btn-primary">
                <i class="fa-solid fa-arrow-left mx-2"></i>Go back
            </a>
        <?php } else { ?>
            <a href="/private?page=view_artifacts" class="btn btn-primary">
                <i class="fa-solid fa-arrow-left mx-2"></i>Go back
            </a>
        <?php } ?>
    </p>
    <h3 class="text-center">Aggiungi rivista</h3>
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
            <label class="form-label" for="MagazineNumber">Numero di rivista</label>
            <input type="number" min="1" max="9999999" name="MagazineNumber" id="MagazineNumber" class="form-control" required />
        </div>
        <div class="form-outline mb-4">
            <label class="form-label" for="Year">Anno</label>
            <input type="number" min="1500" max="2500" name="Year" id="Year" class="form-control" required />
        </div>
        <div class="form-outline mb-4">
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
        <input type='submit' class='btn btn-primary' id='btn-submit'>
        <input type='reset' class='btn btn-info' id='btn-reset'>
    </form>
</div>
<?php $this->push('scripts') ?>
<script src="/api/scripts?filename=fill_select_component.js"></script>
<script>
    let urlPublisher = "/api/generic/components?category=Publisher";
    loadSelect(urlPublisher, "#PublisherID");
    const urlAdd = urlArtifacts;
</script>
<?php if (!isset($_GET['id'])) { ?>
    <script>
        const urlForm = urlArtifactCreate;
    </script>
<?php } else { ?>
    <script>
        const urlForm = urlArtifactUpdate;
    </script>
    <script src="/api/scripts?filename=fill_update_form.js"></script>
    <script>
        fillUpdateForm();
    </script>
<?php } ?>
<script src="/api/scripts?filename=artifact_form.js"></script>
<?php $this->end() ?>