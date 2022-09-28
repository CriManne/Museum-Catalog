<?php $this->layout('layouts::dashboard_layout', ['title' => 'Add book', 'user' => $user]) ?>

<?php $this->push('styles') ?>
<link rel="stylesheet" href="/resources/css/image-overlay.css">
<?php $this->end() ?>
<div class="container-fluid p-0 gap-2 align-items-center w-100" id="main-container">
    <p>
        <a href="/private?page=add_artifact" class="btn btn-primary">
            <i class="fa-solid fa-arrow-left mx-2"></i>Go back
        </a>
    </p>
    <h3 class="text-center">Aggiungi libro</h3>
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
            <select id="PublisherID" name="PublisherID" class="form-select" aria-label="Seleziona un editore" required>
                <option value="" hidden selected>Seleziona un editore</option>
            </select>
        </div>
        <div class="form-outline mb-4">
            <label class="form-label" for="Year">Anno</label>
            <input type="number" min="1500" max="2500" name="Year" id="Year" class="form-control" required />
        </div>
        <div class="form-outline mb-4">
            <label class="form-label" for="Pages">Pagine</label>
            <input type="number" min="1" max="10000" name="Pages" id="Pages" class="form-control" required />
        </div>
        <div class="form-outline mb-4">
            <label class="form-label" for="ISBN">ISBN</label>
            <input type="text" min="10" max="13" name="ISBN" id="ISBN" class="form-control" required />
        </div>
        <div class="form-outline mb-4">
        <label class="form-label" for="newAuthors[]">Seleziona uno o più autori</label>
            <select id="Authors" name="newAuthors[]" class="form-select" aria-label="Seleziona un supporto" multiple="multiple" size="5" required>
                <option value="" hidden selected>Seleziona gli autori</option>
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
        <input type='hidden' name='category' value='Book'>
        <input type='submit' class='btn btn-primary' id='btn-submit'>        
        <input type='reset' class='btn btn-info' id='btn-reset'>
    </form>
</div>
<?php $this->push('scripts') ?>
<script src="/api/scripts?filename=fill_select_component.js"></script>
<script>
    let urlPublisher = "/api/generic/components?category=Publisher";
    loadSelect(urlPublisher, "#PublisherID");
    let urlAuthor = "/api/generic/components?category=Author";
    loadSelect(urlAuthor, "#Authors");
    const urlAdd = urlArtifacts;
</script>
<?php if(!isset($_GET['ObjectID'])){ ?>
    <script> const urlForm = urlArtifactCreate; </script>
<?php }else{ ?>
<script> const urlForm = urlArtifactUpdate; </script>
<script src="/api/scripts?filename=fill_update_form.js"></script>
<script> fillUpdateForm(); </script>
<?php } ?>
<script src="/api/scripts?filename=artifact_form.js"></script>
<?php $this->end() ?>