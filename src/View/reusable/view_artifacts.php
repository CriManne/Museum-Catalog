<div class="py-5 px-3">
    <div class="container-fluid d-flex flex-column p-0 gap-2 align-items-center" id="loading-container">
        <div class="spinner-border mx-auto" role="status"></div>
    </div>
    <div id="main-container" class="d-none">
        <form id='artifact-search-form'>
            <h3 class="text-center mb-5">Visualizza reperti</h3>
            <div id="alert-container"></div>
            <div class="input-group mb-3">
                <select class="input-select" id="category-select">
                    <option selected value="">Tutte le categorie</option>
                </select>
                <input type="text" class="form-control" placeholder="Search artifact" aria-label="Artifact's search" id="artifact-search">
                <input type='submit' class='btn btn-primary'>
            </div>
        </form>
        <div class="container-fluid d-flex flex-column p-0 gap-2 align-items-center" id="result-container">