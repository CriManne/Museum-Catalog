<?php $this->layout('layouts::dashboard_layout', ['title' => $title,'user'=>$user]) ?>
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
<?php $this->insert('reusable::view_artifacts'); ?>
<div class="container-fluid table-scrollable" id="tb-container"></div>
</div>
</div>
</div>
<?php $this->push('scripts') ?>
<script src="/resources/js/search_artifacts.js"></script>
<script src="/api/scripts?filename=/artifact/view_artifacts.js"></script>
<?php $this->end() ?>