<form id='add-user-form'>
    <div class="form-outline mb-4">
        <label class="form-label" for="Email">Email address</label>
        <input type="email" name="Email" id="Email" class="form-control" required/>
    </div>
    <div class="form-outline mb-4">
        <label class="form-label" for="Password">Password</label>
        <input type="text" name="Password" id="Password" class="form-control" required/>
    </div>
    <div class="form-outline mb-4">
        <label class="form-label" for="firstname">Firstname</label>
        <input type="text" name="firstname" id="firstname" class="form-control" required/>
    </div>
    <div class="form-outline mb-4">
        <label class="form-label" for="lastname">Lastname</label>
        <input type="text" name="lastname" id="lastname" class="form-control" required/>
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
<div id="debug-container"></div>
<script>
    <?php require('addUser.js'); ?>
</script>