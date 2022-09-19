<?php

$this->layout('layouts::layout', ['title' => 'Login'])
?>

<?= "Welcome back " . $this->e($user->firstname) . "!"; ?>

<div class='d-flex flex-row justify-content-center gap-3 flex-wrap'>
  <?php
  if ($this->e($user->Privilege) === "1") {
  ?>
    <a class="btn btn-primary" data-bs-toggle="collapse" href="#viewUsers" role="button" aria-expanded="false" aria-controls="viewUsers">
      Visualizza utenti
    </a>
    <a class="btn btn-primary" data-bs-toggle="collapse" href="#addUser" role="button" aria-expanded="false" aria-controls="addUser">
      Aggiungi utente
    </a>
  <?php } ?>
  <a class="btn btn-primary" href="/private/artifacts">
    Visualizza reperti
  </a>
  <a class="btn btn-primary" href="/private/addArtifact">
    Aggiungi reperto
  </a>
  <form action='/private' method='GET'>
    <input type='submit' class="btn btn-primary" name='logout-btn' value='Logout'>
  </form>
</div>
<div id="alert-container"></div>
<?php
if ($this->e($user->Privilege) === "1") {
?>
  <div class="collapse mx-2" id="viewUsers">
    <div class="card card-body">
      <?php
      $this->insert('p_admin::viewUsers');
      ?>
    </div>
  </div>
  <div class="collapse mx-2" id="addUser">
    <div class="card card-body">
      <?php
      $this->insert('p_admin::addUser');
      ?>
    </div>
  </div>
<?php } ?>

<?php $this->push('scripts') ?>
<script src="/resources/js/util.js"></script>
<script src="/resources/js/addUser.js"></script>
<script src="resources/js/viewUsers.js"></script>
<?php $this->end() ?>
