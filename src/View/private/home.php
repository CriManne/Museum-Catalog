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
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#addUser" role="button" aria-expanded="false" aria-controls="addUser">
    Visualizza reperti
  </a>
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#addUser" role="button" aria-expanded="false" aria-controls="addUser">
    Aggiungi reperto
  </a>
  <div class="btn btn-primary" id="logout-btn">
    Logout
  </div>
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



<script>
  <?php require('home.js'); ?>
</script>