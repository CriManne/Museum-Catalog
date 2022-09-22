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
<h3>Visualizza utenti</h3>
<input type="text" class="form-control" placeholder="Search user" aria-label="User's search" id="user-search" required>

<div class="container-fluid d-flex flex-column p-0 gap-2 align-items-center" id="result-container">
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
