<?php $this->layout('layouts::dashboard_layout', ['title' => 'Componenti','user'=>$user]) ?>

<style>
    .table-scrollable {
        overflow-x: auto;
        max-width: 100vw;
        box-shadow: inset 0 0 5px rgba(150, 150, 150, 0.35);
        margin: auto;
        padding: 0;
        padding-top: 10px;
    }
</style>
<div class="container-fluid d-flex flex-column p-0 gap-2 align-items-center" id="loading-container">
    <div class="spinner-border mx-auto" role="status"></div>
</div>
<div id="main-container" class="d-none">
    <form id='component-search-form'>
        <h3>Visualizza componenti</h3>
        <div class="input-group mb-3">
            <select id="template" style="display:none;">
                <option id="templateOption"></option>
            </select>
            <select class="input-select" id="category-select">                
            </select>
            <input type="text" class="form-control" placeholder="Search components" aria-label="Artifact's search" id="component-search">
            <input type='submit' class='btn btn-primary'>
        </div>
    </form>
    <div id="alert-container"></div>
    <div class="container-fluid d-flex flex-column p-0 gap-2 align-items-center" id="result-container">
        <div class="container-fluid table-scrollable" id="tb-container"></div>
    </div>
</div>

<?php $this->push('scripts') ?>
<script src="/api/scripts?filename=/component/search_components.js"></script>
<script src="/api/scripts?filename=/component/view_components.js"></script>
<?php $this->end() ?>