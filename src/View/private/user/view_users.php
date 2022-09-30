<?php $this->layout('layouts::dashboard_layout', ['title' => 'Users', 'user' => $user]) ?>
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
<div class="container-fluid d-flex flex-column p-0 gap-2 align-items-center d-none" id="main-container">
    <div class="w-100">
        <h3>Visualizza utenti</h3>
        <div id="alert-container" class="w-100"></div>
    </div>
    <input type="text" class="form-control" placeholder="Search user" aria-label="User's search" id="user-search" required>
    <div class="container-fluid table-scrollable" id="tb-container"></div>
    <nav id="navigation">
        <ul class="pagination">
            <li class="page-item" id="navigation-prev">
                <div class="page-link" style="cursor:pointer;" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </div>
            </li>
            <div id="paginations" class="d-flex flex-row"></div>

            <li class="page-item" id="navigation-next">
                <div class="page-link" style="cursor:pointer;" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </div>
            </li>
        </ul>
    </nav>
    <select class="form-select w-auto" aria-label="Select per page limit" id="page-limit">
        <option value="5">5</option>
        <option value="10">10</option>
        <option value="25">25</option>
    </select>
</div>
<?php $this->push('scripts') ?>
<script src="/api/adv/scripts?filename=view_users.js"></script>
<?php $this->end() ?>