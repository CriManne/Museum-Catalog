<?php $this->layout('layouts::dashboard_layout', ['title' => $title, 'user' => $user]) ?>

<div class="container-fluid d-flex flex-column p-0 gap-2 align-items-center my-5" id="loading-container">
    <div class="spinner-border mx-auto" role="status"></div>
</div>
<div class="container-fluid p-0 gap-2 align-items-center w-100 d-none" id="main-container">
    <p>
        <?php if (!isset($_GET["id"])) { ?>
            <a href="/private/artifact/choose_artifact_category" class="btn btn-primary">
                <i class="fa-solid fa-arrow-left mx-2"></i>Go back
            </a>
        <?php } else { ?>
            <a href="/private/artifact/view_artifacts" class="btn btn-primary">
                <i class="fa-solid fa-arrow-left mx-2"></i>Go back
            </a>
        <?php } ?>
    </p>
    <h3 class="text-center"><?= $title ?></h3>
    <div id="alert-container"></div>
    <form id='artifact-form' method="POST" enctype="multipart/form-data">
        <div class="form-outline mb-4">
            <label class="form-label" for="genericObject.id">ID</label>
            <input type="text" minlength="20" maxlength="20" name="objectId" id="genericObject.id" class="form-control" <?php if (!isset($_GET['id'])) { ?> required <?php } else { ?> readonly="readonly" <?php } ?> />
        </div>
        <?= $this->section('content'); ?>
        <div class="form-outline mb-4">
            <label class="form-label" for="genericObject.note">Note</label>
            <textarea class="form-control" name="note" id="genericObject.note" rows="3"></textarea>
        </div>
        <div class="form-outline mb-4">
            <label class="form-label" for="genericObject.url">Url</label>
            <input type="text" name="url" id="genericObject.url" class="form-control" />
        </div>
        <div class="form-outline mb-4">
            <label class="form-label" for="genericObject.tag">Tag</label>
            <input type="text" name="tag" id="genericObject.tag" class="form-control" />
        </div>
        <div class="form-outline mb-4" id="images-outer-container"></div>
        <div class="mb-3">
            <label for="images" class="form-label">Images</label>
            <input class="form-control" id="images" name="images[]" type="file" multiple="multiple" accept="image/*">
        </div>
        <input type='submit' class='btn btn-primary' id='btn-submit'>
        <input type='reset' class='btn btn-info' id='btn-reset'>
    </form>
</div>

<?php $this->push('scripts') ?>
<script src="/api/scripts?filename=/artifact/fill_select_component.js"></script>
<?= $this->section('scripts_inner') ?>
<?php if (!isset($_GET['id'])) { ?>
    <script>
        const urlForm = urlArtifactCreate;
    </script>
<?php } else { ?>
    <script>
        const urlForm = urlArtifactUpdate;
    </script>
    <script src="/api/scripts?filename=/artifact/artifact_update_form.js"></script>
    <script>
        fillUpdateForm();
    </script>
<?php } ?>
<script src="/api/scripts?filename=/artifact/artifact_form.js"></script>
<?php $this->end() ?>