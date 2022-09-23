<div class="container-fluid d-flex flex-column p-0 gap-2 align-items-center" id="loading-container">
    <div class="spinner-border mx-auto" role="status"></div>
</div>
<div class="container-fluid p-0 gap-2 align-items-center w-100 d-none" id="main-container">
    <h3 class="text-center">Aggiungi utente</h3>
    <form id='add-user-form'>
        <div class="form-outline mb-4">
            <label class="form-label" for="Email">Email address</label>
            <input type="email" name="Email" id="Email" class="form-control" required />
        </div>
        <div class="form-outline mb-4">
            <label class="form-label" for="Password">Password</label>
            <input type="text" name="Password" id="Password" class="form-control" required />
        </div>
        <div class="form-outline mb-4">
            <label class="form-label" for="firstname">Firstname</label>
            <input type="text" name="firstname" id="firstname" class="form-control" required />
        </div>
        <div class="form-outline mb-4">
            <label class="form-label" for="lastname">Lastname</label>
            <input type="text" name="lastname" id="lastname" class="form-control" required />
        </div>
        <div class="form-outline mb-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" name="Privilege" id="Privilege">
                <label class="form-check-label" for="Privilege">
                    Administrator
                </label>
            </div>
        </div>
        <input type='submit' class='btn btn-primary' id='btn-submit'>
    </form>
</div>