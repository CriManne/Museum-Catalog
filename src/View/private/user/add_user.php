<?php $this->layout('layouts::dashboard_layout', ['title' => $title, 'user' => $user]) ?>
<div class="container-fluid d-flex flex-column p-0 gap-2 align-items-center" id="loading-container">
    <div class="spinner-border mx-auto" role="status"></div>
</div>
<div class="container-fluid p-0 gap-2 align-items-center w-100 d-none" id="main-container">
    <h3 class="text-center">Aggiungi utente</h3>
    <div id="alert-container"></div>
    <form id='add-user-form'>
        <div class="form-outline mb-4">
            <label class="form-label" for="email">Email address</label>
            <input type="email" name="email" id="email" class="form-control" required />
        </div>
        <div class="form-outline mb-4">
            <label class="form-label" for="password">Password</label>
            <input type="password" name="password" id="password" class="form-control" required />
            <input type="checkbox" id="show_psw" onclick="showPsw()">
            <label class="form-label" for="show_psw">Show password</label>
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
                <input class="form-check-input" type="checkbox" value="" name="privilege" id="privilege">
                <label class="form-check-label" for="privilege">
                    Administrator
                </label>
            </div>
        </div>
        <input type='submit' class='btn btn-primary' id='btn-submit'>
    </form>
</div>
<?php $this->push('scripts') ?>
<script src="/resources/js/show_password_toggle.js"></script>
<script src="/api/adv/scripts?filename=add_user.js"></script>
<?php $this->end() ?>