<?php $this->layout('layouts::dashboard_layout', ['title' => $title, 'user' => $user]) ?>

<div class="container-fluid d-flex flex-column p-0 gap-2 align-items-center my-5" id="loading-container">
    <div class="spinner-border mx-auto" role="status"></div>
</div>
<div class="container-fluid p-0 gap-2 align-items-center w-100 d-none" id="main-container">
    <p>
        <?php if (!isset($_GET["id"])) { ?>
            <a href="/private/component?page=choose_component_category&next=add" class="btn btn-primary">
                <i class="fa-solid fa-arrow-left mx-2"></i>Go back
            </a>
        <?php } else { ?>
            <a href="/private/component?page=choose_component_category&next=view" class="btn btn-primary">
                <i class="fa-solid fa-arrow-left mx-2"></i>Go back
            </a>
        <?php } ?>
    </p>
    <?= $this->section('content'); ?>
</div>

<?php $this->push('scripts') ?>
<?php if (!isset($_GET['id'])) { ?>
    <script>
        const urlForm = urlComponentCreate;
    </script>
<?php } else { ?>
    <script>
        const urlForm = urlComponentUpdate;
    </script>
    <script src="/api/scripts?filename=component_update_form.js"></script>
    <script>
        fillUpdateForm();
    </script>
<?php } ?>
<script src="/api/scripts?filename=component_form.js"></script>
<?php $this->end() ?>