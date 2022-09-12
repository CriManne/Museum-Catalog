<?php $this->layout('layouts::layout', ['title' => 'Artifact']) ?>
<script defer>
    <?php require('artifacts.js'); ?>
</script>

<style>
.table-scrollable {
  overflow-x: auto;
  max-width: 100vw;
  box-shadow: inset 0 0 5px rgba(150, 150 ,150,0.35);
  margin: auto;
  padding:0;
  padding-top:10px;
}
</style>
<h3>Visualizza reperti</h3>
<form id='artifact-search-form'>
    <input type="text" class="form-control" placeholder="Search artifact" aria-label="Artifact's search" id="artifact-search" required>
    <input type='submit' class='btn btn-primary'>
</form>

<div class="container-fluid d-flex flex-column p-0 gap-2 align-items-center" id="result-container">
    <div class="container-fluid table-scrollable" id="tb-container"></div>    
</div>
<div class="alert alert-danger" role="alert" id="error-alert"></div>