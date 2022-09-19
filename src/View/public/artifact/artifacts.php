<?php $this->layout('layouts::layout', ['title' => 'Artifact']) ?>

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
<h3>Visualizza reperti</h3>
<form id='artifact-search-form'>

    <div class="input-group mb-3">
        <select id="template" style="display:none;">
            <option id="templateOption"></option>
        </select>
        <!-- this is our "real" template that will be re-sized -->
        <select class="input-select" id="category-select">
            <option selected value="">Tutte le categorie</option>            
        </select>
        <input type="text" class="form-control" placeholder="Search artifact" aria-label="Artifact's search" id="artifact-search" required>
        <input type='submit' class='btn btn-primary'>
    </div>
</form>

<div class="container-fluid d-flex flex-column p-0 gap-2 align-items-center" id="result-container">
    <div class="container-fluid table-scrollable" id="tb-container"></div>
</div>
<div class="alert alert-danger" role="alert" id="error-alert"></div>

<?php $this->push('scripts') ?>
<script src="/resources/js/util.js"></script>
<script src="/resources/js/artifacts.js"></script>
<script>
function setSelectWidth() {
    var sel = $('#sel');
    $('#templateOption').text( sel.val() );
    // for some reason, a small fudge factor is needed
    // so that the text doesn't become clipped
    sel.width( $('#template').width() * 1.03 );
}

setSelectWidth();

$('#sel').change( function() {
    setSelectWidth();
} );
</script>
<?php $this->end() ?>