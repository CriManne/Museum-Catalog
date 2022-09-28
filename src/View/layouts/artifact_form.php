<?php $this->layout('layouts::dashboard_layout', ['title' => $title, 'user' => $user]) ?>

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
    <?= $this->section('content'); ?>
</div>

<?php $this->push('scripts') ?>
<script src="/api/scripts?filename=fill_select_component.js"></script>
<?= $this->section('scripts_inner') ?>
<?php if (!isset($_GET['id'])) { ?>
    <script>
        const urlForm = urlArtifactCreate;
    </script>
<?php } else { ?>
    <script>
        const urlForm = urlArtifactUpdate;
    </script>
    <script src="/api/scripts?filename=update_form.js"></script>
    <script>
        fillUpdateForm();
    </script>
<?php } ?>
<script src="/api/scripts?filename=artifact_form.js"></script>
<?php $this->end() ?>